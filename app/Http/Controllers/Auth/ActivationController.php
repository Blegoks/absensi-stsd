<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ActivationController extends Controller
{
    /**
     * Aktifkan akun berdasarkan token.
     */
    public function activate($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (! $user) {
            return view('auth.activation-failed');
        }

        $user->is_active = true;
        $user->email_verified_at = now();
        $user->activation_token = null;
        $user->save();

        return view('auth.activation-success');
    }
}

