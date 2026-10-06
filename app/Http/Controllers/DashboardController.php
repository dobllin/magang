<?php
// Simpan file ini di: app/Http/Controllers/DashboardController.php (TIMPA yang lama)

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $materi = DB::table('materi')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_materi'   => DB::table('materi')->count(),
            'total_peserta'  => DB::table('users')->where('role', 'peserta')->count(),
            'total_kategori' => DB::table('materi')->distinct('kategori')->count('kategori'),
        ];

        return view('dashboard', compact('materi', 'stats'));
    }
}