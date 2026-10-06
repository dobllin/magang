<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Models\Gambar;
use App\Models\Materi;
use App\Models\Pdf;
use App\Models\Video;
use App\Support\Skema;
use App\Support\Tautan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MateriController extends Controller
{
    use InstrukturAware;

    // ============================================================
    // DAFTAR MATERI
    // ============================================================
    public function index()
    {
        $materis = Materi::where('id_instruktur', $this->idInstrukturSaya())
            ->withCount([
                'soal as jumlah_soal',
                'videos as jumlah_video',
                'pdfs as jumlah_pdf',
                'gambars as jumlah_gambar',
                'progresses as jumlah_peserta',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('instruktur.materi.index', compact('materis'));
    }

    // ============================================================
    // FORM TAMBAH
    // ============================================================
    public function create()
    {
        return view('instruktur.materi.form', [
            'materi'         => new Materi(),
            'mode'           => 'tambah',
            'pilihanJenis'   => Skema::enum('materi', 'jenis_materi'),
            'pilihanStatus'  => Skema::enum('materi', 'status'),
            'videoLama'      => [],
            'pdfLama'        => [],
            'gambarLama'     => [],
        ]);
    }

    // ============================================================
    // SIMPAN BARU
    // ============================================================
    public function store(Request $request)
    {
        $data = $this->validasi($request);

        DB::transaction(function () use ($request, $data) {
            $materi = Materi::create($data + [
                'id_instruktur' => $this->idInstrukturSaya(),
                'slug'          => $this->slugUnik($data['judul']),
            ]);

            $this->simpanLampiran($materi, $request);
        });

        return redirect()
            ->route('instruktur.materi.index')
            ->with('sukses', 'Materi "' . $data['judul'] . '" berhasil dibuat.');
    }

    // ============================================================
    // FORM EDIT
    // ============================================================
    public function edit($id)
    {
        $materi = $this->materiSaya($id);

        return view('instruktur.materi.form', [
            'materi'        => $materi,
            'mode'          => 'edit',
            'pilihanJenis'  => Skema::enum('materi', 'jenis_materi'),
            'pilihanStatus' => Skema::enum('materi', 'status'),
            'videoLama'     => $materi->videos->pluck('video')->all(),
            'pdfLama'       => $materi->pdfs->pluck('pdf_files')->all(),
            'gambarLama'    => $materi->gambars->pluck('picture')->all(),
        ]);
    }

    // ============================================================
    // SIMPAN PERUBAHAN
    // ============================================================
    public function update(Request $request, $id)
    {
        $materi = $this->materiSaya($id);
        $data   = $this->validasi($request, $materi->id);

        DB::transaction(function () use ($materi, $request, $data) {
            // Slug cuma diganti kalau judulnya berubah, biar link
            // yang udah disebar ke peserta nggak tiba-tiba mati.
            if ($materi->judul !== $data['judul']) {
                $data['slug'] = $this->slugUnik($data['judul'], $materi->id);
            }

            $materi->update($data);

            // Lampiran disapu bersih terus ditulis ulang. Lebih simpel
            // dan lebih aman daripada nyocokin satu-satu mana yang berubah.
            $materi->videos()->delete();
            $materi->pdfs()->delete();
            $materi->gambars()->delete();

            $this->simpanLampiran($materi, $request);
        });

        return redirect()
            ->route('instruktur.materi.index')
            ->with('sukses', 'Materi "' . $materi->judul . '" berhasil diperbarui.');
    }

    // ============================================================
    // PREVIEW (tampilan yang dilihat peserta)
    // ============================================================
    public function show($id)
    {
        $materi = $this->materiSaya($id);

        return view('instruktur.materi.show', compact('materi'));
    }

    // ============================================================
    // HAPUS
    // ============================================================
    public function destroy($id)
    {
        $materi = $this->materiSaya($id);

        // Tolak kalau udah ada peserta yang belajar. Menghapusnya
        // bakal bikin riwayat belajar sama nilai mereka ikut hilang.
        if ($materi->progresses()->exists()) {
            return back()->with('gagal', 'Materi ini nggak bisa dihapus karena sudah ada peserta yang mengambilnya. Ubah statusnya jadi draft aja kalau mau disembunyikan.');
        }

        $judul = $materi->judul;

        DB::transaction(function () use ($materi) {
            $materi->videos()->delete();
            $materi->pdfs()->delete();
            $materi->gambars()->delete();
            $materi->soal()->delete();
            $materi->delete();
        });

        return redirect()
            ->route('instruktur.materi.index')
            ->with('sukses', 'Materi "' . $judul . '" sudah dihapus.');
    }

    // ============================================================
    // BAGIAN DALAM
    // ============================================================

    /**
     * Ambil materi HANYA kalau punya instruktur yang lagi login.
     *
     * Ini penting. Tanpa ini, instruktur A tinggal ganti angka di URL
     * buat ngedit atau ngehapus materi punya instruktur B.
     */
    private function materiSaya($id): Materi
    {
        return Materi::with(['videos', 'pdfs', 'gambars'])
            ->where('id_instruktur', $this->idInstrukturSaya())
            ->findOrFail($id);
    }

    private function validasi(Request $request, $abaikanId = null): array
    {
        $pilihanJenis  = Skema::enum('materi', 'jenis_materi');
        $pilihanStatus = Skema::enum('materi', 'status');

        $aturan = [
            'judul'            => ['required', 'string', 'max:255'],
            'kategori'         => ['required', 'string', 'max:100'],
            'subkategori'      => ['nullable', 'string', 'max:100'],
            'deskripsi'        => ['nullable', 'string'],
            'objectives'       => ['nullable', 'string'],
            'bahasa'           => ['nullable', 'string', 'max:50'],
            'duration_minutes' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'total_lessons'    => ['nullable', 'integer', 'min:0', 'max:1000'],
            'learning_points'  => ['nullable', 'integer', 'min:0', 'max:1000'],

            'thumbnail'        => ['nullable', 'string', 'max:500'],

            'video'            => ['nullable', 'array', 'max:20'],
            'video.*'          => ['nullable', 'string', 'max:500'],
            'pdf'              => ['nullable', 'array', 'max:20'],
            'pdf.*'            => ['nullable', 'string', 'max:500'],
            'gambar'           => ['nullable', 'array', 'max:20'],
            'gambar.*'         => ['nullable', 'string', 'max:500'],
        ];

        // Kalau kolomnya ENUM, batasi ke pilihan yang beneran ada di DB.
        // Kalau bukan ENUM, terima teks biasa.
        $aturan['jenis_materi'] = $pilihanJenis
            ? ['required', Rule::in($pilihanJenis)]
            : ['nullable', 'string', 'max:50'];

        $aturan['status'] = $pilihanStatus
            ? ['required', Rule::in($pilihanStatus)]
            : ['nullable', 'string', 'max:50'];

        $pesan = [
            'judul.required'         => 'Judul materi wajib diisi.',
            'kategori.required'      => 'Kategori wajib diisi.',
            'jenis_materi.required'  => 'Pilih dulu jenis materinya.',
            'jenis_materi.in'        => 'Jenis materi itu nggak ada di daftar pilihan.',
            'status.required'        => 'Pilih dulu status materinya.',
            'status.in'              => 'Status itu nggak ada di daftar pilihan.',
            'duration_minutes.integer' => 'Durasi harus berupa angka menit, contohnya 90.',
            'total_lessons.integer'  => 'Jumlah lesson harus berupa angka.',
            'learning_points.integer' => 'Learning point harus berupa angka.',
        ];

        $tervalidasi = $request->validate($aturan, $pesan);

        // Cek link satu per satu. Lebih baik ketahuan sekarang
        // daripada nanti pas peserta yang nemu link-nya rusak.
        $this->cekTautan($request);

        return collect($tervalidasi)
            ->except(['video', 'pdf', 'gambar'])
            ->all();
    }

    private function cekTautan(Request $request): void
    {
        $error = [];

        foreach ((array) $request->input('video', []) as $i => $url) {
            if (filled($url) && ! Tautan::youtubeId($url)) {
                $error["video.{$i}"] = 'Ini bukan link YouTube yang valid. Contoh yang benar: https://www.youtube.com/watch?v=xxxxxxxxxxx';
            }
        }

        foreach ((array) $request->input('pdf', []) as $i => $url) {
            if (filled($url) && ! Tautan::driveId($url)) {
                $error["pdf.{$i}"] = 'Ini bukan link Google Drive yang valid. Contoh yang benar: https://drive.google.com/file/d/xxxxxxxx/view';
            }
        }

        foreach ((array) $request->input('gambar', []) as $i => $url) {
            if (filled($url) && ! Tautan::driveId($url)) {
                $error["gambar.{$i}"] = 'Ini bukan link Google Drive yang valid.';
            }
        }

        $thumb = $request->input('thumbnail');
        if (filled($thumb) && ! Tautan::driveId($thumb) && ! Tautan::youtubeId($thumb) && ! Str::startsWith($thumb, ['http://', 'https://'])) {
            $error['thumbnail'] = 'Thumbnail harus berupa link Google Drive, link YouTube, atau URL gambar yang diawali https://';
        }

        if ($error) {
            throw \Illuminate\Validation\ValidationException::withMessages($error);
        }
    }

    private function simpanLampiran(Materi $materi, Request $request): void
    {
        foreach ((array) $request->input('video', []) as $url) {
            if (blank($url)) {
                continue;
            }
            Video::create([
                'id_materi' => $materi->id,
                'video'     => trim($url),
            ]);
        }

        foreach ((array) $request->input('pdf', []) as $url) {
            if (blank($url)) {
                continue;
            }
            Pdf::create([
                'id_materi' => $materi->id,
                'pdf_files' => trim($url),
            ]);
        }

        foreach ((array) $request->input('gambar', []) as $url) {
            if (blank($url)) {
                continue;
            }
            Gambar::create([
                'id_materi' => $materi->id,
                'picture'   => trim($url),
            ]);
        }
    }

    private function slugUnik(string $judul, $abaikanId = null): string
    {
        $dasar = Str::slug($judul) ?: 'materi';
        $slug  = $dasar;
        $n     = 2;

        while (
            Materi::where('slug', $slug)
                ->when($abaikanId, fn ($q) => $q->where('id', '!=', $abaikanId))
                ->exists()
        ) {
            $slug = $dasar . '-' . $n++;
        }

        return $slug;
    }
}