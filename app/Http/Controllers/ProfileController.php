<?php
// Simpan file ini di: app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_lengkap'  => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'no_hp'         => ['required', 'string', 'max:20'],
            'nis'           => ['required', 'string', 'max:50', 'unique:users,nis,' . $user->id],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat'        => ['required', 'string'],
        ]);

        $user->update([
            'name'          => $request->nama_lengkap,
            'nama_lengkap'  => $request->nama_lengkap,
            'email'         => $request->email,
            'no_hp'         => $request->no_hp,
            'nis'           => $request->nis,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat'        => $request->alamat,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'          => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => $request->password, // auto-hashed via model cast
        ]);

        return back()->with('success', 'Password berhasil diganti.');
    }
}