<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;


class LoginController extends Controller
{
    public function postlogin(Request $request)
{
    // Validate the login form
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ], [
        'email.required' => 'Email harus diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password harus diisi.',
    ]);

    // Attempt to log in using email and password
    if (Auth::attempt([
        'email' => $request->input('email'),
        'password' => $request->input('password')
    ])) {
        // Successful login
        // FIX: Redirect using the route's name to generate the correct URL (/admin/profil)
        return redirect()->route('profil');
    }

    // Jika login gagal
    return redirect('adminibt')
        ->withErrors(['message' => 'Email atau password salah.']);
}

public function getlogin()
{
    // Check if the lockout period has expired
    $lockoutTime = Cache::get('lockout_' . request()->ip());
    if (!$lockoutTime || $lockoutTime <= time()) {
        // Reset login attempts
        Session::forget('login_attempts');
        Cache::forget('lockout_' . request()->ip());
    }
    
    // Load the login view
    return view('admin.login');
}

public function logout(Request $request)
{
    Auth::logout();
    return redirect('adminibt');
}

}