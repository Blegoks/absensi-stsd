<?php

namespace App\Http\Controllers\JuruArah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil sederhana dengan opsi reset password
     */
    public function show()
    {
        $user = Auth::user();
        return view('juruarah.profile', compact('user'));
    }

    /**
     * Proses perubahan password pengguna juru arah
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return back()->with('status', 'Password berhasil diubah.');
    }
}
