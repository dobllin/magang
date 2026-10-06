<?php
// Simpan file ini di: app/Http/Controllers/Auth/RegisteredUserController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    // ===== REGISTER =====
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'  => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'no_hp'         => ['required', 'string', 'max:20'],
            'nis'           => ['required', 'string', 'max:50', 'unique:users,nis'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat'        => ['required', 'string'],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'          => $request->nama_lengkap,
            'nama_lengkap'  => $request->nama_lengkap,
            'email'         => $request->email,
            'no_hp'         => $request->no_hp,
            'nis'           => $request->nis,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat'        => $request->alamat,
            'password'      => $request->password, // auto-hashed via model cast
            'role'          => 'peserta',
            'status'        => 'aktif',
        ]);

        Auth::login($user);

        return redirect()->route('profile.index')->with('success', 'Pendaftaran berhasil! Selamat datang, ' . $user->nama_lengkap . '.');
    }

    // ===== LOGIN =====
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        return match (Auth::user()->role) {
            'admin'      => redirect()->intended(route('admin.dashboard')),
            'instruktur' => redirect()->intended(route('instruktur.dashboard')),
            default      => redirect()->intended(route('profile.index')),
        };
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
}

    // ===== LOGOUT =====
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}