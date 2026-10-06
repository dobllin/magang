<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SertifikatController extends Controller
{
    use InstrukturAware;

    // ============================================================
    // DAFTAR PESERTA YANG LULUS
    // ============================================================
    public function index(Request $request)
    {
        $idInstruktur = $this->idInstrukturSaya();

        $baris = $this->dasarQuery($idInstruktur)
            ->when($request->filled('materi'), function ($q) use ($request) {
                $q->where('materi.id', $request->input('materi'));
            })
            ->when($request->filled('cari'), function ($q) use ($request) {
                $kata = '%' . $request->input('cari') . '%';
                $q->where('users.nama_lengkap', 'like', $kata);
            })
            ->orderBy('penilaian.updated_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $rataNilai = $this->dasarQuery($idInstruktur)->avg('penilaian.nilai');

        return view('instruktur.sertifikat.index', [
            'baris'        => $baris,
            'jumlahLulus'  => $this->dasarQuery($idInstruktur)->count(),
            'rataNilai'    => $rataNilai ? round($rataNilai, 1) : 0,
            'daftarMateri' => Materi::where('id_instruktur', $idInstruktur)
                                ->orderBy('judul')
                                ->get(['id', 'judul']),
            'filterMateri' => $request->input('materi'),
            'kataCari'     => $request->input('cari'),
        ]);
    }

    // ============================================================
    // HALAMAN SERTIFIKAT (siap cetak / simpan jadi PDF)
    // ============================================================
    public function show($idProgress)
    {
        $sertifikat = $this->dasarQuery($this->idInstrukturSaya())
            ->where('progress.id', $idProgress)
            ->first();

        abort_if(! $sertifikat, 404, 'Sertifikat nggak ditemukan, atau peserta ini belum lulus.');

        // Nomor sertifikat dibentuk dari id progres biar konsisten
        // tiap kali halaman ini dibuka, bukan angka acak.
        $sertifikat->nomor = sprintf(
            'PPKPI/%s/%s/%04d',
            date('Y', strtotime($sertifikat->lulus_pada)),
            str_pad($sertifikat->id_materi, 3, '0', STR_PAD_LEFT),
            $sertifikat->id_progress
        );

        return view('instruktur.sertifikat.show', compact('sertifikat'));
    }

    // ============================================================
    // BAGIAN DALAM
    // ============================================================

    /**
     * Query dasar peserta lulus.
     *
     * "Lulus" di sini artinya salah satu dari dua ini:
     *   - penilaian.status = 'lulus'
     *   - progress.progres = 'sertif'
     *
     * Dua-duanya dipakai karena alur pengisian di project ini
     * belum tentu selalu ngisi kedua kolom itu barengan.
     */
    private function dasarQuery(int $idInstruktur)
    {
        return DB::table('progress')
            ->join('users', 'progress.id_user', '=', 'users.id')
            ->join('materi', 'progress.id_materi', '=', 'materi.id')
            ->join('penilaian', 'penilaian.id_progress', '=', 'progress.id')
            ->join('instruktur', 'materi.id_instruktur', '=', 'instruktur.id')
            ->join('users as pengajar', 'instruktur.id_user', '=', 'pengajar.id')
            ->where('materi.id_instruktur', $idInstruktur)
            ->where(function ($q) {
                $q->where('penilaian.status', 'lulus')
                  ->orWhere('progress.progres', 'sertif');
            })
            ->select(
                'progress.id as id_progress',
                'progress.progres',
                'users.id as id_user',
                'users.nama_lengkap',
                'users.email',
                'users.nis',
                'materi.id as id_materi',
                'materi.judul as judul_materi',
                'materi.kategori',
                'materi.duration_minutes',
                'penilaian.nilai',
                'penilaian.status as status_penilaian',
                'penilaian.updated_at as lulus_pada',
                'pengajar.nama_lengkap as nama_instruktur',
                'instruktur.jabatan as jabatan_instruktur'
            );
    }
}