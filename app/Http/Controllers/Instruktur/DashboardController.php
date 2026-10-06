<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
                $user = Auth::user();
        $instruktur = $user->instruktur;

        if (!$instruktur) {
            return redirect()->route('dashboard')
                ->with('error', 'Akun ini nggak punya profil instruktur.');
        }

        $idInstruktur = $instruktur->id;

        // ============================================
        // STATS OVERVIEW (4 Card Utama)
        // ============================================
        $totalMateri = Materi::where('id_instruktur', $idInstruktur)->count();

        $totalPeserta = DB::table('progress')
            ->join('materi', 'progress.id_materi', '=', 'materi.id')
            ->where('materi.id_instruktur', $idInstruktur)
            ->distinct('progress.id_user')
            ->count('progress.id_user');

        $totalLulus = DB::table('penilaian')
            ->join('progress', 'penilaian.id_progress', '=', 'progress.id')
            ->join('materi', 'progress.id_materi', '=', 'materi.id')
            ->where('materi.id_instruktur', $idInstruktur)
            ->where('penilaian.status', 'lulus')
            ->distinct('penilaian.id_user')
            ->count('penilaian.id_user');

        $rataNilai = DB::table('penilaian')
            ->join('progress', 'penilaian.id_progress', '=', 'progress.id')
            ->join('materi', 'progress.id_materi', '=', 'materi.id')
            ->where('materi.id_instruktur', $idInstruktur)
            ->whereNotNull('penilaian.nilai')
            ->avg('penilaian.nilai');
        $rataNilai = $rataNilai ? round($rataNilai, 1) : 0;

        // ============================================
        // LIST MATERI + STATS
        // ============================================
        $materis = Materi::where('id_instruktur', $idInstruktur)
            ->withCount([
                'progresses as jumlah_peserta' => function($q) {
                    $q->select(DB::raw('COUNT(DISTINCT id_user)'));
                },
                'progresses as jumlah_lulus' => function($q) {
                    $q->select(DB::raw('COUNT(DISTINCT id_user)'))->where('progres', 'sertif');
                },
                'soal as jumlah_soal',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // ============================================
        // AKTIVITAS PESERTA TERBARU (10 latest)
        // ============================================
        $aktivitasTerbaru = DB::table('progress')
            ->join('users', 'progress.id_user', '=', 'users.id')
            ->join('materi', 'progress.id_materi', '=', 'materi.id')
            ->leftJoin('penilaian', 'penilaian.id_progress', '=', 'progress.id')
            ->where('materi.id_instruktur', $idInstruktur)
            ->select(
                'progress.id',
                'progress.progres',
                'progress.created_at',
                'users.nama_lengkap',
                'users.foto',
                'materi.judul as judul_materi',
                'penilaian.status as status_penilaian',
                'penilaian.nilai'
            )
            ->orderBy('progress.created_at', 'desc')
            ->limit(10)
            ->get();

        // ============================================
        // DISTRIBUSI PROGRESS (Buat Chart)
        // ============================================
        $distribusiProgress = DB::table('progress')
            ->join('materi', 'progress.id_materi', '=', 'materi.id')
            ->where('materi.id_instruktur', $idInstruktur)
            ->select('progress.progres', DB::raw('COUNT(DISTINCT progress.id_user) as jumlah'))
            ->groupBy('progress.progres')
            ->get()
            ->pluck('jumlah', 'progres')
            ->toArray();

        $distribusiProgress = [
            'pretes' => $distribusiProgress['pretes'] ?? 0,
            'view' => $distribusiProgress['view'] ?? 0,
            'postes' => $distribusiProgress['postes'] ?? 0,
            'sertif' => $distribusiProgress['sertif'] ?? 0,
        ];

        // ============================================
        // TOP 5 MATERI POPULER
        // ============================================
        $materiPopuler = DB::table('materi')
            ->leftJoin('progress', 'materi.id', '=', 'progress.id_materi')
            ->where('materi.id_instruktur', $idInstruktur)
            ->select(
                'materi.id',
                'materi.judul',
                'materi.kategori',
                'materi.learning_points',
                DB::raw('COUNT(DISTINCT progress.id_user) as jumlah_peserta'),
                DB::raw('COUNT(DISTINCT CASE WHEN progress.progres = "sertif" THEN progress.id_user END) as jumlah_lulus')
            )
            ->groupBy('materi.id', 'materi.judul', 'materi.kategori', 'materi.learning_points')
            ->orderBy('jumlah_peserta', 'desc')
            ->limit(5)
            ->get();

        return view('instruktur.dashboard', compact(
            'user',
            'instruktur',
            'totalMateri',
            'totalPeserta',
            'totalLulus',
            'rataNilai',
            'materis',
            'aktivitasTerbaru',
            'distribusiProgress',
            'materiPopuler'
        ));
    }
}