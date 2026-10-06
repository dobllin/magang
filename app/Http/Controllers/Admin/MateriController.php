<?php
// Simpan file ini di: app/Http/Controllers/Admin/MateriController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MateriController extends Controller
{
    public function index()
    {
        $materi = DB::table('materi')->orderBy('created_at', 'desc')->get();
        return view('admin.materi.index', compact('materi'));
    }

    public function create()
    {
        $instrukturs = DB::table('instruktur')
            ->join('users', 'instruktur.id_user', '=', 'users.id')
            ->select('instruktur.id', 'users.nama_lengkap')
            ->get();

        return view('admin.materi.form', ['materi' => null, 'instrukturs' => $instrukturs]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = Str::slug($data['judul']);
        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('materi')->insert($data);

        return redirect()->route('admin.materi.index')->with('success', 'Materi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $materi = DB::table('materi')->where('id', $id)->first();
        if (!$materi) {
            abort(404);
        }

        $instrukturs = DB::table('instruktur')
            ->join('users', 'instruktur.id_user', '=', 'users.id')
            ->select('instruktur.id', 'users.nama_lengkap')
            ->get();

        return view('admin.materi.form', compact('materi', 'instrukturs'));
    }

    public function update(Request $request, $id)
    {
        $data = $this->validateData($request);
        $data['slug'] = Str::slug($data['judul']);
        $data['updated_at'] = now();

        DB::table('materi')->where('id', $id)->update($data);

        return redirect()->route('admin.materi.index')->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DB::table('materi')->where('id', $id)->delete();
        return back()->with('success', 'Materi berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'id_instruktur'    => ['required', 'integer'],
            'judul'            => ['required', 'string', 'max:255'],
            'kategori'         => ['required', 'string', 'max:100'],
            'subkategori'      => ['nullable', 'string', 'max:100'],
            'deskripsi'        => ['required', 'string'],
            'jenis_materi'     => ['required', 'string', 'max:50'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'learning_points'  => ['required', 'integer', 'min:0'],
            'total_lessons'    => ['required', 'integer', 'min:1'],
            'bahasa'           => ['required', 'string', 'max:50'],
            'status'           => ['required', 'string', 'max:50'],
        ]);

        return $validated;
    }
}