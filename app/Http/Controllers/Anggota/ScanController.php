<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\SesiAbsensi;
use App\Models\Anggota;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScanController extends Controller
{
    public function index()
    {
        return view('anggota.scan');
    }

    /**
     * Absensi manual dengan upload foto
     */
    public function submit(Request $request)
    {
        if (!$request->hasFile('image')) {
            return response()->json(['status' => 'error', 'message' => 'Gambar tidak ditemukan'], 400);
        }

        $image = $request->file('image');
        $imageData = base64_encode(file_get_contents($image->getRealPath()));

        $response = Http::post('http://localhost:5000/recognize', [
            'image' => $imageData,
        ]);

        if ($response->ok() && $response['status'] === 'success') {
            // Gunakan profile_id dari user login
            $user = Auth::user();
            $idAnggota = $user->profile_id;

            $sesi = SesiAbsensi::where('is_open', true)->latest()->first();
            if (!$sesi) {
                return response()->json(['status' => 'error', 'message' => 'Tidak ada sesi aktif.'], 400);
            }

            $sudahAbsen = Absensi::where('anggota_id', $idAnggota)
                ->where('sesi_absensi_id', $sesi->id)
                ->exists();

            if ($sudahAbsen) {
                return response()->json(['status' => 'error', 'message' => 'Sudah absen.'], 400);
            }

            Absensi::create([
                'anggota_id' => $idAnggota,
                'sesi_absensi_id' => $sesi->id,
                'tanggal' => Carbon::today(),
                'status' => 'hadir',
            ]);

            return response()->json(['status' => 'success', 'message' => 'Absensi berhasil dicatat.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Wajah tidak dikenali atau server tidak merespons.']);
    }

    /**
     * Absensi otomatis (dipanggil dari Python)
     */
    public function scanStore(Request $request)
    {
        // Ambil ID anggota dari user login, bukan dari request
        $idAnggota = Auth::user()->profile_id;

        $sesi = SesiAbsensi::where('is_open', true)->latest()->first();
        if (!$sesi) {
            return response()->json(['status' => 'error', 'message' => 'Tidak ada sesi aktif.'], 400);
        }

        $sudahAbsen = Absensi::where('anggota_id', $idAnggota)
            ->where('sesi_absensi_id', $sesi->id)
            ->whereDate('tanggal', Carbon::today())
            ->exists();

        if ($sudahAbsen) {
            return response()->json(['status' => 'error', 'message' => 'Sudah absen.'], 400);
        }

        Absensi::create([
            'anggota_id' => $idAnggota,
            'sesi_absensi_id' => $sesi->id,
            'tanggal' => Carbon::today(),
            'status' => 'hadir',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Absensi berhasil.']);
    }

    /**
     * Absensi via webcam realtime (dari web)
     */
    public function scanViaWebcam(Request $request)
{
    $base64Image = $request->input('image');

    if (!$base64Image) {
        return response()->json(['status' => 'error', 'message' => 'Gambar tidak ditemukan.']);
    }

    // Decode gambar dari base64
    $base64Image = str_replace('data:image/jpeg;base64,', '', $base64Image);
    $image = base64_decode($base64Image);

    // Simpan sementara ke file
    $tmpPath = storage_path('app/tmp_wajah.jpg');
    file_put_contents($tmpPath, $image);

    // Kirim ke Python API
    $response = Http::attach('image', file_get_contents($tmpPath), 'wajah.jpg')
        ->asMultipart()
        ->post('http://127.0.0.1:5050/anggota/scan', [
            'id_juruarah' => Auth::user()->anggota->juru_arah_id
        ]);

    // Hapus file sementara
    @unlink($tmpPath);

    // Handle response
    if ($response->ok()) {
        $result = $response->json();

        if ($result['status'] === 'success') {
            $idAnggotaDariWajah = $result['id_anggota'];
            $idAnggotaLogin = Auth::user()->profile_id;

            // Cek apakah wajah yang dikenali milik akun yang sedang login
            if ($idAnggotaDariWajah != $idAnggotaLogin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Wajah tidak cocok dengan akun yang sedang login.'
                ]);
            }

            // Lanjutkan proses absensi
            $sesi = SesiAbsensi::where('is_open', true)->latest()->first();
            if (!$sesi) {
                return response()->json(['status' => 'error', 'message' => 'Tidak ada sesi absensi aktif.']);
            }

            $sudahAbsen = Absensi::where('anggota_id', $idAnggotaDariWajah)
                ->where('sesi_absensi_id', $sesi->id)
                ->whereDate('tanggal', now()->toDateString())
                ->exists();

            if ($sudahAbsen) {
                return response()->json(['status' => 'error', 'message' => 'Anggota sudah melakukan absensi hari ini.']);
            }

            Absensi::create([
                'anggota_id' => $idAnggotaDariWajah,
                'sesi_absensi_id' => $sesi->id,
                'tanggal' => now()->toDateString(),
                'jam' => now()->format('H:i:s'),
                'status' => 'hadir',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Absensi berhasil dicatat.'
            ]);
        }

        // Jika wajah tidak dikenali
        return response()->json([
            'status' => 'error',
            'message' => $result['message'] ?? 'Wajah tidak dikenali.'
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'Gagal menghubungi server deteksi wajah.'
    ]);
}

}
