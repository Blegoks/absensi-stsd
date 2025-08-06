<?php

namespace App\Http\Controllers\JuruArah;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Tempekan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Mail\SendActivationMail;
use Illuminate\Support\Facades\Mail;

class DaftarAnggotaController extends Controller
{
    public function index()
    {
        $juruArah = Auth::user()->juruArah;

        if (!$juruArah) {
            abort(403, 'Anda bukan juru arah');
        }

        $tempekanIds = $juruArah->tempekan->pluck('id');

        $anggotaList = Anggota::whereIn('tempekan_id', $tempekanIds)->get();

        return view('juruarah.anggota.index', compact('anggotaList'));
    }


public function delete($id)
{
    $user = User::findOrFail($id);

    if ($user->role === 'juruarah') {
        return back()->with('error', 'Tidak bisa menghapus admin.');
    }

    // Jika user adalah anggota dan punya profile_id, hapus juga data anggota terkait
    if ($user->role === 'anggota' && $user->profile_id) {
        \App\Models\Anggota::where('id', $user->profile_id)->delete();
    }

    $user->delete();
    return back()->with('success', 'Pengguna berhasil dihapus.');
}

public function create()
{
    $juruArah = Auth::user()->juruArah;
    $tempekan = $juruArah->tempekan->first();

    if (!$tempekan) {
        return redirect()->back()->with('error', 'Anda belum memiliki tempekan.');
    }

    return view('juruarah.anggota.create', compact('tempekan'));
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:anggota',
    ]);

    $juruArah = Auth::user()->juruArah;
    $tempekan = $juruArah->tempekan->first();

    if (!$tempekan) {
        return redirect()->back()->with('error', 'Anda belum memiliki tempekan.');
    }

    // Buat user
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make('password'); // default password
    $user->role = $request->role;
    $user->save();

    // Buat data anggota
    $anggota = new Anggota();
    $anggota->user_id = $user->id;
    $anggota->tempekan_id = $tempekan->id;
    $anggota->save();

    return redirect()->route('anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
}

}
