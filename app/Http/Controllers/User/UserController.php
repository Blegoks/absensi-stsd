<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SesiAbsensi;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function dashboard()
{
    /** @var User $user */
    $user = Auth::user();

    $sesiAktif = SesiAbsensi::where('is_open', true)->latest()->first();
    $absensiHariIni = Absensi::where('user_id', $user->id)
        ->whereDate('created_at', Carbon::today())
        ->first();

    return view('user.dashboard', compact('sesiAktif', 'absensiHariIni'));
}
    
}
