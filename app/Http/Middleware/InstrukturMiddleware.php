<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class InstrukturMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek udah login belum
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Silakan login dulu.');
        }

        // Cek role-nya instruktur atau admin
        $user = Auth::user();
        if (!in_array($user->role, ['instruktur', 'admin'])) {
            abort(403, 'Halaman ini khusus instruktur.');
        }

        // Cek profil instruktur udah dibuat belum (kalau role = instruktur)
        if ($user->role === 'instruktur' && !$user->instruktur) {
            abort(403, 'Profil instruktur belum dibuat. Hubungi admin.');
        }

        return $next($request);
    }
}