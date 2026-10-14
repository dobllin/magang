<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsInstruktur
{
    /**
     * Area instruktur cuma buat user ber-role instruktur yang udah
     * punya profil. Admin punya panelnya sendiri di /admin, jadi nggak
     * ikut masuk ke sini (semua halaman instruktur butuh profil instruktur).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        $user = Auth::user();

        if ($user->role !== 'instruktur') {
            abort(403, 'Halaman ini khusus instruktur.');
        }

        if (!$user->instruktur) {
            abort(403, 'Profil instruktur belum dibuat. Hubungi admin.');
        }

        return $next($request);
    }
}
