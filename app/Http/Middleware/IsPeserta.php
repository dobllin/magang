<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsPeserta
{
    /**
     * Fitur belajar (ikut materi, tes, sertifikat) cuma buat peserta.
     * Admin dan instruktur punya dashboard masing-masing.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        if (Auth::user()->role !== 'peserta') {
            abort(403, 'Fitur belajar khusus akun peserta.');
        }

        return $next($request);
    }
}
