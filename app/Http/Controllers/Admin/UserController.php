<?php
// Simpan file ini di: app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form', ['user' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email'],
            'no_hp'        => ['required', 'string', 'max:20'],
            'role'         => ['required', 'in:peserta,instruktur,admin'],
            'password'     => ['required', Rules\Password::defaults()],
        ]);

        User::create([
            'name'         => $data['nama_lengkap'],
            'nama_lengkap' => $data['nama_lengkap'],
            'email'        => $data['email'],
            'no_hp'        => $data['no_hp'],
            'role'         => $data['role'],
            'password'     => $data['password'], // auto-hashed via model cast
            'status'       => 'aktif',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email,' . $user->id],
            'no_hp'        => ['required', 'string', 'max:20'],
            'role'         => ['required', 'in:peserta,instruktur,admin'],
            'password'     => ['nullable', Rules\Password::defaults()],
        ]);

        $updateData = [
            'name'         => $data['nama_lengkap'],
            'nama_lengkap' => $data['nama_lengkap'],
            'email'        => $data['email'],
            'no_hp'        => $data['no_hp'],
            'role'         => $data['role'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = $data['password'];
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}