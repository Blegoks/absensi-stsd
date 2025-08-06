<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Anggota;
use App\Models\Absensi;
use App\Models\SesiAbsensi;
use App\Models\JuruArah;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendActivationMail;
use App\Exports\RekapAnggotaExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiAnggotaExport;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    /**
     * Dashboard utama admin
     */
    public function index()
    {
        $totalUser = User::count();
        $totalAnggota = Anggota::count();
        $totalJuruArah = JuruArah::count();
        $totalAbsensi = Absensi::count();
        $todayAbsensi = Absensi::whereDate('tanggal', Carbon::today())->count();

        return view('admin.board', compact(
            'totalUser',
            'totalAnggota',
            'totalJuruArah',
            'totalAbsensi',
            'todayAbsensi'
        ));
    }

    /**
     * Statistik Kehadiran
     */
    public function attendance()
    {
        $today = Carbon::today();
        $absensiHariIni = Absensi::whereDate('tanggal', $today)->get();
        $totalHadir = $absensiHariIni->where('status', 'hadir')->count();
        $totalIzin = $absensiHariIni->where('status', 'izin')->count();
        $totalTidakhadir = $absensiHariIni->where('status', 'tidak hadir')->count();

        $absenPerSesi = SesiAbsensi::withCount('absensi')->orderBy('dibuka_pada', 'desc')->limit(10)->get();

        return view('admin.attendance', compact(
            'absensiHariIni',
            'totalHadir',
            'totalIzin',
            'totalTidakhadir',
            'absenPerSesi'
        ));
    }

    /**
     * Manajemen Pengguna: Admin, Juru Arah, Anggota
     */
    public function manageJuruArah()
{
    $juruarahList = User::where('role', 'juruarah')->get();
    return view('admin.manage.juruarah', compact('juruarahList'));
}

public function manageAnggota()
{
    $anggotaList = User::where('role', 'anggota')->with('anggota')->get();
    return view('admin.manage.anggota', compact('anggotaList'));
}



public function deleteUser($id)
{
    $user = User::findOrFail($id);

    if ($user->role === 'admin') {
        return back()->with('error', 'Tidak bisa menghapus admin.');
    }

    // Jika user adalah anggota dan punya profile_id, hapus juga data anggota terkait
    if ($user->role === 'anggota' && $user->profile_id) {
        \App\Models\Anggota::where('id', $user->profile_id)->delete();
    }

    $user->delete();
    return back()->with('success', 'Pengguna berhasil dihapus.');
}

public function createUser(Request $request)
{
    $role = $request->query('role'); // ambil ?role=anggota dari URL
    $tempekans = \App\Models\Tempekan::all();

    return view('admin.create-user', compact('tempekans', 'role'));
}


public function storeUser(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:admin,anggota,juruarah',
        'tempekan' => 'required_if:role,anggota',
    ], [
        'tempekan.required_if' => 'Tempekan wajib diisi jika role adalah anggota.',
    ]);

    // Buat password random dan token aktivasi
    $randomPassword = Str::random(10);
    $activationToken = Str::uuid();

    // Buat user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($randomPassword),
        'role' => $request->role,
        'activation_token' => $activationToken,
        'is_active' => false,
    ]);

    if ($request->role === 'anggota') {
        // Buat data anggota
        $anggota = new \App\Models\Anggota();
        $anggota->user_id = $user->id;
        $anggota->nama = $request->name;
        $anggota->email = $request->email;
        $anggota->tempekan_id = $request->tempekan;

        // Ambil juru_arah_id dari tempekan
        $tempekan = \App\Models\Tempekan::find($request->tempekan);
        $anggota->juru_arah_id = $tempekan ? $tempekan->juruarah_id : null;
        $anggota->save();

        // Simpan ke profile_id user
        $user->profile_id = $anggota->id;
        $user->save();
    }

    if ($request->role === 'juruarah') {
        // Buat data juru arah
        $juruarah = new \App\Models\JuruArah();
        $juruarah->user_id = $user->id;
        $juruarah->nama = $request->name;
        $juruarah->email = $request->email;
        $juruarah->save();

        // Simpan ke profile_id user
        $user->profile_id = $juruarah->id;
        $user->save();
    }

    // Kirim email aktivasi & password
    Mail::to($user->email)->send(new SendActivationMail($user, $randomPassword));

    return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan dan password dikirim ke email.');
}
    /**
     * Board Ringkasan Kegiatan
     */
    public function boards()
{
    $terbaru = Absensi::with(['anggota.tempekan'])
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

    // Absensi 6 bulan terakhir (untuk grafik bulan)
    $monthlyAbsensi = Absensi::selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as bulan, COUNT(*) as total')
        ->where('tanggal', '>=', Carbon::now()->subMonths(5)->startOfMonth())
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->pluck('total', 'bulan');

    $labels = [];
    $data = [];
    for ($i = 5; $i >= 0; $i--) {
        $bulan = Carbon::now()->subMonths($i)->format('Y-m');
        $labels[] = Carbon::now()->subMonths($i)->translatedFormat('F Y');
        $data[] = $monthlyAbsensi[$bulan] ?? 0;
    }

    // === Tambahkan: Grafik Per Tempekan (6 bulan terakhir) ===
    $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();

    $tempekanStats = DB::table('absensi')
        ->join('anggota', 'absensi.anggota_id', '=', 'anggota.id')
        ->join('tempekan', 'anggota.tempekan_id', '=', 'tempekan.id')
        ->select(
            'tempekan.nama as tempekan',
            DB::raw("SUM(CASE WHEN absensi.status = 'hadir' THEN 1 ELSE 0 END) as hadir"),
            DB::raw("SUM(CASE WHEN absensi.status = 'izin' THEN 1 ELSE 0 END) as izin"),
            DB::raw("SUM(CASE WHEN absensi.status = 'tidak hadir' THEN 1 ELSE 0 END) as tidakhadir")
        )
        ->where('absensi.tanggal', '>=', $sixMonthsAgo)
        ->groupBy('tempekan.nama')
        ->get();

    $tempekanLabels = $tempekanStats->pluck('tempekan');
    $tempekanHadir = $tempekanStats->pluck('hadir');
    $tempekanIzin = $tempekanStats->pluck('izin');
    $tempekanTidakhadir = $tempekanStats->pluck('tidakhadir');

    return view('admin.boards', [
        'totalUser' => User::count(),
        'totalAnggota' => \App\Models\Anggota::count(),
        'totalJuruArah' => \App\Models\JuruArah::count(),
        'totalAbsensi' => \App\Models\Absensi::count(),
        'todayAbsensi' => \App\Models\Absensi::whereDate('tanggal', \Carbon\Carbon::today())->count(),
        'terbaru' => $terbaru,
        'chartLabels' => $labels,
        'chartData' => $data,

        // Data untuk grafik tempekan
        'tempekanLabels' => $tempekanLabels,
        'tempekanHadir' => $tempekanHadir,
        'tempekanIzin' => $tempekanIzin,
        'tempekanTidakhadir' => $tempekanTidakhadir,
    ]);
}

    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function rekapJuruarah()
{
    $juruarahList = User::where('role', 'juruarah')->get();
    return view('admin.rekap.juruarah-index', compact('juruarahList'));
}

public function rekapAnggotaByJuruarah(Request $request, $id)
{
    $juruarah = User::findOrFail($id);

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

    return view('admin.rekap.anggota-index', compact('juruarah', 'anggotaList', 'tanggalMulai', 'tanggalSelesai'));
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

    return view('admin.rekap.absensi-detail', [
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


public function editJuruArah($id)
{
    $user = User::where('id', $id)->where('role', 'juruarah')->firstOrFail();
    $profile = JuruArah::findOrFail($user->profile_id);

    return view('admin.manage.edit-juruarah', compact('user', 'profile'));
}


public function updateJuruArah(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
    ]);

    $user = User::findOrFail($id);
    $user->name = $request->name;
    $user->email = $request->email;
    $user->save();

    $profile = JuruArah::find($user->profile_id);
    if ($profile) {
        $profile->nama = $request->name;
        $profile->email = $request->email;
        $profile->save();
    }

    return redirect()->route('admin.manage.juruarah')->with('success', 'Data Juru Arah berhasil diperbarui.');
}


}
