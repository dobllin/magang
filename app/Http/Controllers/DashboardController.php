<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Materi draft nggak ditampilkan ke publik.
        $materi = DB::table('materi')
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_materi'   => DB::table('materi')->where('status', 'published')->count(),
            'total_peserta'  => DB::table('users')->where('role', 'peserta')->count(),
            'total_kategori' => DB::table('materi')->where('status', 'published')->distinct()->count('kategori'),
        ];

        return view('dashboard', compact('materi', 'stats'));
    }
}