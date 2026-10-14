<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Penilaian;
use App\Models\Progress;
use App\Support\Penilai;
use App\Support\Sertifikat;
use App\Support\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Alur belajar peserta di satu materi:
 *
 *   ikuti → pretes → view (belajar) → postes → sertif
 *
 * Tahapnya disimpan di kolom progress.progres. Pretes dilewati kalau
 * instruktur belum bikin soal pretes. Postes bisa diulang sampai lulus.
 */
class KelasController extends Controller
{
    // ============================================================
    // KELAS SAYA
    // ============================================================
    public function index()
    {
        $kelas = Progress::with(['materi.instruktur.user', 'penilaian'])
            ->where('id_user', Auth::id())
            ->latest('updated_at')
            ->get();

        return view('kelas.index', compact('kelas'));
    }

    // ============================================================
    // DETAIL MATERI (bisa dibuka tanpa login)
    // ============================================================
    public function show(Materi $materi)
    {
        abort_unless($materi->status === 'published', 404);

        $materi->load('instruktur.user')
            ->loadCount([
                'soal as jumlah_pretes' => fn ($q) => $q->where('jenis_soal', 'pretes'),
                'soal as jumlah_postes' => fn ($q) => $q->where('jenis_soal', 'postes'),
                'videos', 'pdfs', 'gambars',
            ]);

        $progress = Auth::check()
            ? Progress::with('penilaian')->where('id_user', Auth::id())->where('id_materi', $materi->id)->first()
            : null;

        return view('kelas.materi', compact('materi', 'progress'));
    }

    // ============================================================
    // IKUTI MATERI
    // ============================================================
    public function ikuti(Materi $materi)
    {
        abort_unless($materi->status === 'published', 404);

        $progress = Progress::firstOrCreate(
            ['id_user' => Auth::id(), 'id_materi' => $materi->id],
            ['progres' => $this->soal($materi, 'pretes')->isNotEmpty() ? 'pretes' : 'view']
        );

        return redirect()->to($this->langkahBerikutnya($materi, $progress))
            ->with('sukses', 'Selamat belajar! Kamu sudah terdaftar di materi ini.');
    }

    // ============================================================
    // HALAMAN BELAJAR
    // ============================================================
    public function belajar(Materi $materi)
    {
        $progress = $this->progressSaya($materi);

        if ($progress->progres === 'pretes') {
            return redirect()->route('kelas.tes', [$materi, 'pretes'])
                ->with('gagal', 'Kerjakan pretes dulu sebelum mulai belajar.');
        }

        $materi->load(['videos', 'pdfs', 'gambars', 'instruktur.user']);
        $adaPostes = $this->soal($materi, 'postes')->isNotEmpty();

        return view('kelas.belajar', compact('materi', 'progress', 'adaPostes'));
    }

    /** Tandai selesai belajar, lanjut ke postes. */
    public function selesaiBelajar(Materi $materi)
    {
        $progress = $this->progressSaya($materi);

        if ($progress->progres === 'view') {
            $progress->update(['progres' => 'postes']);
        }

        return redirect()->to($this->langkahBerikutnya($materi, $progress));
    }

    // ============================================================
    // PRETES / POSTES
    // ============================================================
    public function tes(Materi $materi, string $jenis)
    {
        $progress = $this->progressSaya($materi);

        if ($alasan = $this->tolakTes($progress, $jenis)) {
            return redirect()->to($this->langkahBerikutnya($materi, $progress))->with('gagal', $alasan);
        }

        $soal = $this->soal($materi, $jenis);

        // Soal pretes dihapus setelah peserta terdaftar: langsung lanjut belajar.
        if ($soal->isEmpty() && $jenis === 'pretes') {
            $progress->update(['progres' => 'view']);

            return redirect()->route('kelas.belajar', $materi);
        }

        if ($soal->isEmpty()) {
            return redirect()->route('kelas.belajar', $materi)
                ->with('gagal', 'Instruktur belum menyiapkan soal ' . $jenis . ' untuk materi ini. Coba lagi nanti.');
        }

        return view('kelas.tes', compact('materi', 'progress', 'soal', 'jenis'));
    }

    public function kirimTes(Request $request, Materi $materi, string $jenis)
    {
        $progress = $this->progressSaya($materi);

        if ($alasan = $this->tolakTes($progress, $jenis)) {
            return redirect()->to($this->langkahBerikutnya($materi, $progress))->with('gagal', $alasan);
        }

        $soal = $this->soal($materi, $jenis);
        abort_if($soal->isEmpty(), 404);

        $request->validate([
            'jawaban'   => ['required', 'array'],
            'jawaban.*' => ['nullable', 'string', 'max:2000'],
        ], [
            'jawaban.required' => 'Isi dulu jawabanmu.',
        ]);

        $hasil = Penilai::nilai($soal, (array) $request->input('jawaban', []));

        // Pretes cuma buat ngukur kemampuan awal, nggak nentuin kelulusan.
        if ($jenis === 'pretes') {
            $progress->update(['progres' => 'view']);

            return redirect()->route('kelas.belajar', $materi)->with(
                'sukses',
                "Pretes selesai: {$hasil['benar']} dari {$hasil['total']} benar (nilai {$hasil['nilai']}). Sekarang pelajari materinya."
            );
        }

        $kkm   = config('pelatihan.kkm');
        $lulus = $hasil['nilai'] >= $kkm;

        DB::transaction(function () use ($progress, $hasil, $lulus) {
            Penilaian::updateOrCreate(
                ['id_progress' => $progress->id],
                [
                    'id_user' => $progress->id_user,
                    'nilai'   => $hasil['nilai'],
                    'status'  => $lulus ? 'lulus' : $this->statusTidakLulus(),
                ]
            );

            if ($lulus) {
                $progress->update(['progres' => 'sertif']);
            }
        });

        if ($lulus) {
            return redirect()->route('kelas.sertifikat', $progress->id)->with(
                'sukses',
                "Selamat, kamu lulus dengan nilai {$hasil['nilai']}!"
            );
        }

        return redirect()->route('kelas.belajar', $materi)->with(
            'gagal',
            "Nilai postes kamu {$hasil['nilai']} ({$hasil['benar']} dari {$hasil['total']} benar). "
            . "Minimal {$kkm} untuk lulus. Pelajari lagi materinya, lalu coba postes sekali lagi."
        );
    }

    // ============================================================
    // SERTIFIKAT SAYA
    // ============================================================
    public function sertifikat($idProgress)
    {
        $sertifikat = Sertifikat::query()
            ->where('progress.id', $idProgress)
            ->where('progress.id_user', Auth::id())
            ->first();

        abort_if(! $sertifikat, 404, 'Sertifikat nggak ditemukan, atau kamu belum lulus materi ini.');

        $sertifikat->nomor = Sertifikat::nomor($sertifikat);

        return view('sertifikat.cetak', [
            'sertifikat' => $sertifikat,
            'kembali'    => route('kelas.index'),
        ]);
    }

    // ============================================================
    // BAGIAN DALAM
    // ============================================================

    /** Progres user yang lagi login di materi ini. Belum ikut = 404. */
    private function progressSaya(Materi $materi): Progress
    {
        return Progress::where('id_user', Auth::id())
            ->where('id_materi', $materi->id)
            ->firstOr(fn () => abort(redirect()->route('materi.show', $materi)
                ->with('gagal', 'Ikuti materinya dulu ya.')));
    }

    private function soal(Materi $materi, string $jenis)
    {
        return $materi->soal()->where('jenis_soal', $jenis)->orderBy('id')->get();
    }

    /** Balikin alasan kalau tes ini nggak boleh dikerjakan sekarang. */
    private function tolakTes(Progress $progress, string $jenis): ?string
    {
        return match (true) {
            $jenis === 'pretes' && $progress->progres !== 'pretes' => 'Pretes cuma dikerjakan sekali di awal.',
            $jenis === 'postes' && $progress->progres === 'sertif' => 'Kamu sudah lulus materi ini.',
            $jenis === 'postes' && $progress->progres !== 'postes' => 'Selesaikan dulu materinya sebelum postes.',
            default => null,
        };
    }

    private function langkahBerikutnya(Materi $materi, Progress $progress): string
    {
        return match ($progress->progres) {
            'pretes' => route('kelas.tes', [$materi, 'pretes']),
            'postes' => route('kelas.tes', [$materi, 'postes']),
            'sertif' => route('kelas.sertifikat', $progress->id),
            default  => route('kelas.belajar', $materi),
        };
    }

    /**
     * Nilai "tidak lulus" diambil dari ENUM di database kalau ada,
     * biar nggak kena "Data truncated" di database lama.
     */
    private function statusTidakLulus(): string
    {
        $pilihan = array_values(array_diff(Skema::enum('penilaian', 'status'), ['lulus']));

        return $pilihan[0] ?? 'tidak_lulus';
    }
}
