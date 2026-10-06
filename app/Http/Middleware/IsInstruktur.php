<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsInstruktur
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek udah login belum
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        $user = Auth::user();

        // Cek role: harus instruktur atau admin
        // (admin boleh akses area instruktur juga buat monitoring)
        if (!in_array($user->role, ['instruktur', 'admin'])) {
            abort(403, 'Halaman ini khusus instruktur.');
        }

        // Cek profil instruktur udah dibuat (khusus role instruktur)
        if ($user->role === 'instruktur' && !$user->instruktur) {
            abort(403, 'Profil instruktur belum dibuat. Hubungi admin.');
        }

        return $next($request);
    }
}