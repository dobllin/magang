<?php

namespace App\Http\Controllers\Instruktur;

use Illuminate\Support\Facades\Auth;

/**
 * Dipakai bareng-bareng sama semua controller area instruktur.
 *
 * Ada dua tugas:
 * 1. Ngambil profil instruktur punya user yang lagi login.
 * 2. Nolak duluan kalau profilnya nggak ada, biar nggak muncul
 *    error "Attempt to read property id on null" yang bikin bingung.
 */
trait InstrukturAware
{
    protected function instrukturSaya()
    {
        $instruktur = Auth::user()?->instruktur;

        abort_if(
            ! $instruktur,
            403,
            'Akun ini belum punya profil instruktur. Hubungi admin buat dibuatkan.'
        );

        return $instruktur;
    }

    protected function idInstrukturSaya(): int
    {
        return $this->instrukturSaya()->id;
    }
}