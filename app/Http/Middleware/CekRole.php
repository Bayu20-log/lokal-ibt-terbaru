<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
      // Cek apakah user sudah login
      if (!Auth::check()) {
        // Kalau belum login, redirect ke halaman login
        return redirect('adminibt');
    }

    if (!in_array(Auth::user()->role, $roles)) {
      abort(403, 'Anda tidak memiliki akses ke halaman ini.');
  }




    // // Cek apakah role user ada di dalam daftar role yang diizinkan
    // if (!in_array(Auth::user()->role, $roles)) {
    //     // Kalau role tidak cocok, tolak akses
    //     abort(403, '');
    // }

    // Kalau semua cocok, lanjutkan
    return $next($request);
}
}
