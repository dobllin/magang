<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Support\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return view('admin.materi.form', [
            'materi'        => null,
            'instrukturs'   => $instrukturs,
            'pilihanJenis'  => self::pilihanJenis(),
            'pilihanStatus' => self::pilihanStatus(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = Materi::slugUnik($data['judul']);
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

        return view('admin.materi.form', [
            'materi'        => $materi,
            'instrukturs'   => $instrukturs,
            'pilihanJenis'  => self::pilihanJenis(),
            'pilihanStatus' => self::pilihanStatus(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $materi = DB::table('materi')->where('id', $id)->first();
        if (!$materi) {
            abort(404);
        }

        $data = $this->validateData($request);
        // Slug cuma diganti kalau judulnya berubah, biar link lama nggak mati.
        if ($materi->judul !== $data['judul']) {
            $data['slug'] = Materi::slugUnik($data['judul'], $id);
        }
        $data['updated_at'] = now();

        DB::table('materi')->where('id', $id)->update($data);

        return redirect()->route('admin.materi.index')->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $materi = Materi::findOrFail($id);

        // Sama seperti di area instruktur: jangan hapus materi yang udah
        // diambil peserta, nanti riwayat belajar dan nilainya ikut hilang.
        if ($materi->progresses()->exists()) {
            return back()->with('error', 'Materi ini sudah diambil peserta, jadi nggak bisa dihapus. Ubah statusnya jadi draft aja.');
        }

        DB::transaction(function () use ($materi) {
            $materi->videos()->delete();
            $materi->pdfs()->delete();
            $materi->gambars()->delete();
            $materi->soal()->delete();
            $materi->delete();
        });

        return back()->with('success', 'Materi berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'id_instruktur'    => ['required', 'integer', 'exists:instruktur,id'],
            'judul'            => ['required', 'string', 'max:255'],
            'kategori'         => ['required', 'string', 'max:100'],
            'subkategori'      => ['nullable', 'string', 'max:100'],
            'deskripsi'        => ['required', 'string'],
            'jenis_materi'     => ['required', 'in:' . implode(',', self::pilihanJenis())],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'learning_points'  => ['required', 'integer', 'min:0'],
            'total_lessons'    => ['required', 'integer', 'min:1'],
            'bahasa'           => ['required', 'string', 'max:50'],
            'status'           => ['required', 'in:' . implode(',', self::pilihanStatus())],
        ]);

        return $validated;
    }

    // Pilihan diambil dari ENUM di database biar sama persis dengan
    // form instruktur. Kalau kolomnya bukan ENUM, pakai daftar bawaan.
    private static function pilihanJenis(): array
    {
        return Skema::enum('materi', 'jenis_materi') ?: ['artikel', 'video', 'pdf', 'quiz'];
    }

    private static function pilihanStatus(): array
    {
        return Skema::enum('materi', 'status') ?: ['draft', 'published'];
    }
}
