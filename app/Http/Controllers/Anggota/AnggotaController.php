<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\SesiAbsensi;
use App\Models\Absensi;
use Carbon\Carbon;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = Auth::user()->anggota; // Perbaikan nama relasi: seharusnya 'anggota', bukan 'Anggot'

        if (!$anggota) {
            return abort(403, 'Akun ini tidak terkait dengan data anggota.');
        }

        $today = Carbon::today();

        $sesiAktif = SesiAbsensi::where('is_open', true)->latest()->first();

        // Cek hanya jika sesi aktif ada
        $absensiHariIni = null;
        if ($sesiAktif) {
            $absensiHariIni = Absensi::where('anggota_id', $anggota->id)
                ->whereDate('tanggal', $today)
                ->first();
        }

        $riwayat = Absensi::where('anggota_id', $anggota->id)
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        return view('anggota.layout', compact('sesiAktif', 'absensiHariIni', 'riwayat'));
    }

    public function showDashboard()
    {
        $anggota = Auth::user()->anggota;

        if (!$anggota) {
            return abort(403, 'Akun ini tidak terkait dengan data anggota.');
        }

        $today = Carbon::today();
        $sesiAktif = SesiAbsensi::where('is_open', true)->latest()->first();

        $absensiHariIni = null;
        if ($sesiAktif) {
            $absensiHariIni = Absensi::where('anggota_id', $anggota->id)
                ->whereDate('tanggal', $today)
                ->first();
        }

        $riwayat = Absensi::where('anggota_id', $anggota->id)
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        return view('anggota.dashboard', compact('sesiAktif', 'absensiHariIni', 'riwayat'));
    }
    
public function showFormWajah()
{
    return view('anggota.daftar_wajah');
}

public function prosesDaftarWajah(Request $request)
{
    $id = $request->id_anggota;
    $nama = $request->nama;

    // Path ke Python executable (ganti sesuai lokasi Python 3.10 kamu)
    $pythonPath = "C:\Users\User\AppData\Local\Programs\Python\Python310\python.exe";

    // Path ke script Python
    $scriptPath = public_path("daftar_wajah/daftar_wajah.py");

    // Bangun command dengan benar
    $command = "\"{$pythonPath}\" \"{$scriptPath}\" {$id} \"{$nama}\"";

    // Eksekusi perintah dan tangkap output
    exec($command . " 2>&1", $output, $status);

    // Logging untuk debug jika diperlukan
    Log::info("Command: " . $command);
    Log::info("Output: ", $output);
    Log::info("Status: " . $status);

    // Cek status
    if ($status === 0) {
        return back()->with('success', 'Wajah berhasil didaftarkan.')->with('output', $output);
    } else {
        return back()->with('error', 'Gagal mendaftarkan wajah. Pastikan kamera aktif.')->with('output', $output);
    }
}



}
