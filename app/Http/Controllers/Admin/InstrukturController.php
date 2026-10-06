<?php
// Simpan file ini di: app/Http/Controllers/Admin/InstrukturController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstrukturController extends Controller
{
    public function index()
    {
        $instruktur = DB::table('instruktur')
            ->join('users', 'instruktur.id_user', '=', 'users.id')
            ->select('instruktur.*', 'users.nama_lengkap', 'users.email')
            ->orderBy('instruktur.created_at', 'desc')
            ->get();

        return view('admin.instruktur.index', compact('instruktur'));
    }

    public function create()
    {
        // Cuma user yang rolenya instruktur & belum ada di tabel instruktur
        $users = DB::table('users')
            ->where('role', 'instruktur')
            ->whereNotIn('id', DB::table('instruktur')->pluck('id_user'))
            ->get(['id', 'nama_lengkap']);

        return view('admin.instruktur.form', ['instruktur' => null, 'users' => $users]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('instruktur')->insert($data);

        return redirect()->route('admin.instruktur.index')->with('success', 'Instruktur berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $instruktur = DB::table('instruktur')->where('id', $id)->first();
        if (!$instruktur) {
            abort(404);
        }

        $users = DB::table('users')->where('role', 'instruktur')->get(['id', 'nama_lengkap']);

        return view('admin.instruktur.form', compact('instruktur', 'users'));
    }

    public function update(Request $request, $id)
    {
        $data = $this->validateData($request);
        $data['updated_at'] = now();

        DB::table('instruktur')->where('id', $id)->update($data);

        return redirect()->route('admin.instruktur.index')->with('success', 'Instruktur berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DB::table('instruktur')->where('id', $id)->delete();
        return back()->with('success', 'Instruktur berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'id_user'       => ['required', 'integer'],
            'spesialisasi'  => ['required', 'string', 'max:255'],
            'pengalaman'    => ['required', 'string', 'max:100'],
            'sertifikasi'   => ['nullable', 'string', 'max:255'],
            'jabatan'       => ['required', 'string', 'max:100'],
        ]);
    }
}