<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesertaController extends Controller
{
    use InstrukturAware;

    public function index(Request $request)
    {
        $idInstruktur = $this->idInstrukturSaya();

        $baris = DB::table('progress')
            ->join('users', 'progress.id_user', '=', 'users.id')
            ->join('materi', 'progress.id_materi', '=', 'materi.id')
            ->leftJoin('penilaian', 'penilaian.id_progress', '=', 'progress.id')
            ->where('materi.id_instruktur', $idInstruktur)

            // Filter: satu materi aja
            ->when($request->filled('materi'), function ($q) use ($request) {
                $q->where('materi.id', $request->input('materi'));
            })

            // Filter: tahap progres tertentu (pretes / view / postes / sertif)
            ->when($request->filled('tahap'), function ($q) use ($request) {
                $q->where('progress.progres', $request->input('tahap'));
            })

            // Cari nama atau email peserta
            ->when($request->filled('cari'), function ($q) use ($request) {
                $kata = '%' . $request->input('cari') . '%';
                $q->where(function ($sub) use ($kata) {
                    $sub->where('users.nama_lengkap', 'like', $kata)
                        ->orWhere('users.email', 'like', $kata);
                });
            })

            ->select(
                'progress.id as id_progress',
                'progress.progres',
                'progress.created_at as mulai_pada',
                'users.id as id_user',
                'users.nama_lengkap',
                'users.email',
                'users.no_hp',
                'users.foto',            // kolomnya 'foto', BUKAN 'photo'
                'materi.id as id_materi',
                'materi.judul as judul_materi',
                'materi.kategori',
                'penilaian.nilai',
                'penilaian.status as status_penilaian'
            )
            ->orderBy('progress.created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Ringkasan angka di atas tabel
        $ringkasan = DB::table('progress')
            ->join('materi', 'progress.id_materi', '=', 'materi.id')
            ->where('materi.id_instruktur', $idInstruktur)
            ->select('progress.progres', DB::raw('COUNT(DISTINCT progress.id_user) as jumlah'))
            ->groupBy('progress.progres')
            ->pluck('jumlah', 'progres')
            ->toArray();

        $totalPeserta = DB::table('progress')
            ->join('materi', 'progress.id_materi', '=', 'materi.id')
            ->where('materi.id_instruktur', $idInstruktur)
            ->distinct('progress.id_user')
            ->count('progress.id_user');

        return view('instruktur.peserta.index', [
            'baris'        => $baris,
            'ringkasan'    => $ringkasan,
            'totalPeserta' => $totalPeserta,
            'daftarMateri' => Materi::where('id_instruktur', $idInstruktur)
                                ->orderBy('judul')
                                ->get(['id', 'judul']),
            'filterMateri' => $request->input('materi'),
            'filterTahap'  => $request->input('tahap'),
            'kataCari'     => $request->input('cari'),
        ]);
    }
}