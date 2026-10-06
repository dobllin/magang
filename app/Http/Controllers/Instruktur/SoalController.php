<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Soal;
use App\Support\Skema;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SoalController extends Controller
{
    use InstrukturAware;

    // ============================================================
    // DAFTAR SOAL
    // ============================================================
    public function index(Request $request)
    {
        $idInstruktur = $this->idInstrukturSaya();

        $soals = Soal::with('materi')
            ->where('id_instruktur', $idInstruktur)
            // Filter opsional: cuma tampilkan soal dari satu materi
            ->when($request->filled('materi'), function ($q) use ($request) {
                $q->where('id_materi', $request->input('materi'));
            })
            // Filter opsional: cuma pretes atau cuma postes
            ->when($request->filled('jenis'), function ($q) use ($request) {
                $q->where('jenis_soal', $request->input('jenis'));
            })
            ->orderBy('id_materi')
            ->orderBy('id')
            ->get()
            ->groupBy(fn ($s) => $s->materi->judul ?? 'Materi sudah dihapus');

        return view('instruktur.soal.index', [
            'soalPerMateri' => $soals,
            'daftarMateri'  => $this->materiSaya(),
            'pilihanJenis'  => Skema::enum('soal', 'jenis_soal'),
            'filterMateri'  => $request->input('materi'),
            'filterJenis'   => $request->input('jenis'),
        ]);
    }

    // ============================================================
    // FORM TAMBAH
    // ============================================================
    public function create(Request $request)
    {
        $daftarMateri = $this->materiSaya();

        if ($daftarMateri->isEmpty()) {
            return redirect()
                ->route('instruktur.materi.create')
                ->with('gagal', 'Bikin materi dulu sebelum menyusun soal, karena setiap soal harus nempel ke satu materi.');
        }

        return view('instruktur.soal.form', [
            'soal'         => new Soal(['id_materi' => $request->input('materi')]),
            'mode'         => 'tambah',
            'daftarMateri' => $daftarMateri,
            'pilihanJenis' => Skema::enum('soal', 'jenis_soal'),
        ]);
    }

    // ============================================================
    // SIMPAN BARU
    // ============================================================
    public function store(Request $request)
    {
        $data = $this->validasi($request);

        Soal::create($data + ['id_instruktur' => $this->idInstrukturSaya()]);

        // Balik ke form lagi biar bisa langsung nulis soal berikutnya
        return redirect()
            ->route('instruktur.soal.create', ['materi' => $data['id_materi']])
            ->with('sukses', 'Soal tersimpan. Silakan lanjut ke soal berikutnya.');
    }

    // ============================================================
    // FORM EDIT
    // ============================================================
    public function edit($id)
    {
        return view('instruktur.soal.form', [
            'soal'         => $this->soalSaya($id),
            'mode'         => 'edit',
            'daftarMateri' => $this->materiSaya(),
            'pilihanJenis' => Skema::enum('soal', 'jenis_soal'),
        ]);
    }

    // ============================================================
    // SIMPAN PERUBAHAN
    // ============================================================
    public function update(Request $request, $id)
    {
        $soal = $this->soalSaya($id);
        $soal->update($this->validasi($request));

        return redirect()
            ->route('instruktur.soal.index')
            ->with('sukses', 'Soal berhasil diperbarui.');
    }

    // ============================================================
    // HAPUS
    // ============================================================
    public function destroy($id)
    {
        $this->soalSaya($id)->delete();

        return back()->with('sukses', 'Soal sudah dihapus.');
    }

    // ============================================================
    // BAGIAN DALAM
    // ============================================================

    /** Semua materi punya instruktur yang lagi login. */
    private function materiSaya()
    {
        return Materi::where('id_instruktur', $this->idInstrukturSaya())
            ->orderBy('judul')
            ->get(['id', 'judul']);
    }

    /** Ambil soal HANYA kalau punya instruktur yang lagi login. */
    private function soalSaya($id): Soal
    {
        return Soal::where('id_instruktur', $this->idInstrukturSaya())
            ->findOrFail($id);
    }

    private function validasi(Request $request): array
    {
        $pilihanJenis = Skema::enum('soal', 'jenis_soal');

        $aturan = [
            // Pastikan materinya beneran punya instruktur ini,
            // bukan materi orang lain yang id-nya diketik manual.
            'id_materi' => [
                'required',
                Rule::exists('materi', 'id')->where(
                    fn ($q) => $q->where('id_instruktur', $this->idInstrukturSaya())
                ),
            ],
            'pertanyaan'    => ['required', 'string'],
            'kunci_jawaban' => ['required', 'string'],
        ];

        $aturan['jenis_soal'] = $pilihanJenis
            ? ['required', Rule::in($pilihanJenis)]
            : ['nullable', 'string', 'max:50'];

        return $request->validate($aturan, [
            'id_materi.required'     => 'Pilih dulu materinya.',
            'id_materi.exists'       => 'Materi itu bukan punya Anda.',
            'pertanyaan.required'    => 'Pertanyaan wajib diisi.',
            'kunci_jawaban.required' => 'Kunci jawaban wajib diisi.',
            'jenis_soal.required'    => 'Pilih dulu soal ini buat pretes atau postes.',
            'jenis_soal.in'          => 'Jenis soal itu nggak ada di daftar pilihan.',
        ]);
    }
}