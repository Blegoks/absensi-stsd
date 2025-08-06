<?php

namespace App\Http\Controllers\JuruArah;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\SesiAbsensi;
use App\Models\Absensi;
use Carbon\Carbon;

class SesiAbsensiController extends Controller
{
    public function showBukaSesi()
    {
        $kegiatan = ['Gotong Royong', 'Rapat Anggota'];
        return view('juruarah.sesi.buka', compact('kegiatan'));
    }

    public function bukaSesi(Request $request)
    {
        $request->validate([
            'jenis_kegiatan' => 'required|in:Gotong Royong,Rapat Anggota',
        ]);

        SesiAbsensi::where('juruarah_id', Auth::user()->profile_id)
            ->where('is_open', true)
            ->update([
                'is_open' => false,
                'ditutup_pada' => Carbon::now(),
            ]);

        SesiAbsensi::create([
            'juruarah_id' => Auth::user()->profile_id,
            'dibuka_pada' => now(),
            'is_open' => true,
            'jenis_kegiatan' => $request->jenis_kegiatan,
        ]);

        return redirect()->back()->with('success', 'Sesi absensi berhasil dibuka.');
    }

    public function showTutupSesi()
    {
        $sesiAktif = SesiAbsensi::where('juruarah_id', Auth::user()->profile_id)
            ->where('is_open', true)
            ->latest()
            ->first();

        return view('juruarah.sesi.tutup', compact('sesiAktif'));
    }

    public function tutupSesi(Request $request)
    {
        $sesi = SesiAbsensi::where('juruarah_id', Auth::user()->profile_id)
            ->where('is_open', true)
            ->latest()
            ->first();

        if ($sesi) {
            $sesi->update([
                'ditutup_pada' => Carbon::now(),
                'is_open' => false,
            ]);

            // Otomatis buat absensi status 'tidak_hadir' untuk anggota yang belum absen
            $juruArah = Auth::user()->juruArah;
            $tempekanIds = $juruArah->tempekan->pluck('id');
            $anggotaList = \App\Models\Anggota::whereIn('tempekan_id', $tempekanIds)->get();
            foreach ($anggotaList as $anggota) {
                $sudahAbsen = \App\Models\Absensi::where('anggota_id', $anggota->id)
                    ->where('sesi_absensi_id', $sesi->id)
                    ->exists();
                if (!$sudahAbsen) {
                    \App\Models\Absensi::create([
                        'anggota_id' => $anggota->id,
                        'sesi_absensi_id' => $sesi->id,
                        'status' => 'tidak_hadir',
                        'tanggal' => \Carbon\Carbon::parse($sesi->dibuka_pada)->toDateString(),
                        'jam' => null,
                        'keterangan' => 'Tidak melakukan absensi',
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Sesi absensi berhasil ditutup. Absensi tidak hadir otomatis dicatat.');
        }

        return redirect()->back()->with('error', 'Tidak ada sesi aktif yang dapat ditutup.');
    }

    public function simpanIzin(Request $request)
{
    $request->validate([
        'anggota_id' => 'required|exists:anggota,id',
        'sesi_absensi_id' => 'required|exists:sesi_absensi,id',
        'keterangan' => 'required|string',
    ]);

    $sesi = \App\Models\SesiAbsensi::find($request->sesi_absensi_id);

    if (!$sesi || !$sesi->is_open) {
        return back()->with('error', 'Sesi absensi tidak tersedia atau sudah ditutup.');
    }

    // Simpan sebagai izin
    \App\Models\Absensi::updateOrCreate(
        [
            'anggota_id' => $request->anggota_id,
            'tanggal' => \Carbon\Carbon::parse($sesi->dibuka_pada)->toDateString(),
            'sesi_absensi_id' => $sesi->id
        ],
        [
            'status' => 'izin',
            'keterangan' => $request->keterangan,
            'jam' => now()->toTimeString()
        ]
    );

    return back()->with('success', 'Izin berhasil dicatat.');
}


    public function konfirmasiIzin(Request $request, $id)
    {
        $request->validate([
            'aksi' => 'required|in:terima,tolak',
        ]);

        $absensi = Absensi::findOrFail($id);
        $absensi->status = $request->aksi === 'terima' ? 'diterima' : 'ditolak';
        $absensi->save();

        return back()->with('success', 'Izin berhasil dikonfirmasi.');
    }
}
