<?php

namespace App\Http\Controllers\JuruArah;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;  
use App\Models\Anggota;
use App\Models\User;
use App\Models\Absensi;   
use App\Models\SesiAbsensi;
use Illuminate\Support\Str;  
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapAnggotaExport;
use App\Exports\AbsensiAnggotaExport;

class JuruArahController extends Controller
{
    // Konfirmasi izin oleh Juru Arah

    public function index(){
        $juruArah = Auth::user()->juruArah;
        $tempekanIds = $juruArah->tempekan->pluck('id');

        // Daftar anggota berdasarkan tempekan juru arah
        $anggotaList = Anggota::whereIn('tempekan_id', $tempekanIds)->with('user')->get();
        $jumlahAnggota = $anggotaList->count();

        // Cek sesi absensi yang sedang dibuka (aktif)
        $sesiAktif = \App\Models\SesiAbsensi::where('juruarah_id', $juruArah->id)
            ->where('is_open', true)
            ->latest('dibuka_pada')
            ->first();

        if ($sesiAktif) {
            // Ambil absensi hanya untuk sesi aktif
            $absensiAktif = \App\Models\Absensi::whereIn('anggota_id', $anggotaList->pluck('id'))
                ->where('sesi_absensi_id', $sesiAktif->id)
                ->get();
            $totalHadir = $absensiAktif->where('status', 'hadir')->count();
        } else {
            $totalHadir = 0;
        }

        // Total absensi seluruh waktu (rekap)
        $absensi = \App\Models\Absensi::whereIn('anggota_id', $anggotaList->pluck('id'))->get();
        $totalAbsensi = $absensi->count();


        // Rekap bar chart 1 bulan terakhir per anggota
        $startDate = now()->subMonth()->startOfDay();
        $endDate = now()->endOfDay();
        $absensiBulan = \App\Models\Absensi::whereIn('anggota_id', $anggotaList->pluck('id'))
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        $chartLabels = [];
        $hadirData = [];
        $izinData = [];
        $tidakHadirData = [];

        foreach ($anggotaList as $anggota) {
            $chartLabels[] = $anggota->nama;
            $hadirData[] = $absensiBulan->where('anggota_id', $anggota->id)->where('status', 'hadir')->count();
            $izinData[] = $absensiBulan->where('anggota_id', $anggota->id)->where('status', 'izin')->count();
            $tidakHadirData[] = $absensiBulan->where('anggota_id', $anggota->id)->where('status', 'tidak_hadir')->count();
        }

        $chartData = [
            'labels' => $chartLabels,
            'hadir' => $hadirData,
            'izin' => $izinData,
            'tidak_hadir' => $tidakHadirData,
        ];

        return view('juruarah.board',compact(
            'anggotaList',
            'jumlahAnggota',
            'absensi',
            'totalHadir',
            'totalAbsensi',
            'chartData'
        ));
    }


  public function monitoring()
{
    $juruArah = Auth::user()->juruArah;
    if (!$juruArah) {
        abort(403, 'Anda bukan juru arah');
    }

    $sesiAktif = SesiAbsensi::where('juruarah_id', $juruArah->id)
        ->where('is_open', true)
        ->latest('dibuka_pada')
        ->first();

    $anggota = collect();
    if ($sesiAktif) {
        $tempekanIds = $juruArah->tempekan->pluck('id');

        $anggota = Anggota::with([
            'absensi' => function($q) use ($sesiAktif) {
                $q->where('sesi_absensi_id', $sesiAktif->id);
            }
        ])->whereIn('tempekan_id', $tempekanIds)->get();
    }

    return view('juruarah.monitoring', [
        'anggota' => $anggota,
        'sesiAktif' => $sesiAktif ? [$sesiAktif] : [],
    ]);
}


    public function rekapTabel(Request $request)
{
    $juruarah = Auth::user();

    $tanggalMulai = $request->input('tanggal_mulai');
    $tanggalSelesai = $request->input('tanggal_selesai');

    $query = Anggota::where('juru_arah_id', $juruarah->profile_id)
        ->with(['absensi' => function ($q) use ($tanggalMulai, $tanggalSelesai) {
            if ($tanggalMulai && $tanggalSelesai) {
                $q->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
            }
        }]);

    if ($request->filled('search')) {
        $query->where('nama', 'like', '%' . $request->search . '%');
    }

    $anggotaList = $query->get();

    return view('juruarah.rekap.index', compact('juruarah', 'anggotaList', 'tanggalMulai', 'tanggalSelesai'));
}

public function exportRekapAnggota(Request $request, $userId)
{
    $user = User::findOrFail($userId);

    if ($user->role !== 'juruarah') {
        abort(403, 'Hanya juru arah yang dapat direkap.');
    }

    $juruArahProfileId = $user->profile_id;

    $tanggalMulai = $request->input('tanggal_mulai');
    $tanggalSelesai = $request->input('tanggal_selesai');

    return Excel::download(
        new RekapAnggotaExport($juruArahProfileId, $tanggalMulai, $tanggalSelesai),
        'rekap_anggota.xlsx'
    );
}



public function rekapDetailAbsensi(Request $request, $id)
{
   $anggota = Anggota::with(['absensi.sesiAbsensi'])->findOrFail($id);

    $query = $anggota->absensi();

    if ($request->filled('start_date')) {
        $query->whereDate('tanggal', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('tanggal', '<=', $request->end_date);
    }

    $filteredAbsensi = $query->orderBy('tanggal', 'desc')->get();

    return view('juruarah.rekap.rekap_detail', [
    'anggota' => $anggota,
    'absensi' => $filteredAbsensi,
]);
}

public function exportAbsensiAnggota($id)
{
    $anggota = Anggota::findOrFail($id);

    $filename = 'rekap_absensi_' . Str::slug($anggota->nama) . '.xlsx';

    return Excel::download(new AbsensiAnggotaExport($id), $filename);
}
public function storeIzin(Request $request)
{
    $request->validate([
        'anggota_id' => 'required|exists:anggota,id',
        'sesi_absensi_id' => 'required|exists:sesi_absensi,id',
        'keterangan' => 'required|string|max:255',
    ]);

    // Cek apakah absensi sudah ada
    $existing = Absensi::where('anggota_id', $request->anggota_id)
        ->where('sesi_absensi_id', $request->sesi_absensi_id)
        ->first();

    if ($existing) {
        return back()->with('error', 'Absensi sudah dicatat.');
    }

    Absensi::create([
        'anggota_id' => $request->anggota_id,
        'sesi_absensi_id' => $request->sesi_absensi_id,
        'status' => 'izin',
        'keterangan' => $request->keterangan,
        'tanggal' => now()->toDateString(),
    ]);

    return back()->with('success', 'Status diubah menjadi Izin.');
}



    
}
