<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Support\Sertifikat;
use Illuminate\Http\Request;

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

        $sertifikat->nomor = Sertifikat::nomor($sertifikat);

        return view('sertifikat.cetak', [
            'sertifikat' => $sertifikat,
            'kembali'    => route('instruktur.sertifikat.index'),
        ]);
    }

    // ============================================================
    // BAGIAN DALAM
    // ============================================================

    /** Peserta lulus di materi-materi punya instruktur ini. */
    private function dasarQuery(int $idInstruktur)
    {
        return Sertifikat::query()->where('materi.id_instruktur', $idInstruktur);
    }
}
