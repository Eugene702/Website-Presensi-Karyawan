<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek jika pengguna yang login memiliki role yang sesuai
        if (Auth::check() && Auth::user()->role == $role) {
            // Jika sesuai, lanjutkan request
            return $next($request);
        }

        // Jika tidak sesuai, hentikan dan tampilkan halaman error 403 (Forbidden)
        abort(403, 'AKSES DITOLAK: ANDA TIDAK MEMILIKI HAK AKSES UNTUK HALAMAN INI.');
    }
}
