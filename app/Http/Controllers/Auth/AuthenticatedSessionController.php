<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi login.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        // Cek apakah user ditemukan dan belum aktif
        if ($user && !$user->is_active) {
            throw ValidationException::withMessages([
                'email' => 'Akun Anda belum aktif. Silakan cek email Anda untuk aktivasi.',
            ]);
        }

        // Lanjut autentikasi bawaan
        $request->authenticate();
        $request->session()->regenerate();

        // Arahkan berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->route('admin.board');
        } elseif ($user->role === 'juruarah') {
            return redirect()->route('juruarah.board');
        } elseif ($user->role === 'anggota') {
            return redirect()->route('anggota.layout');
        }

        // Fallback jika role tidak dikenali
        Auth::logout();
        return redirect()->route('login')->withErrors(['role' => 'Role tidak dikenali.']);
    }

    /**
     * Logout user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
