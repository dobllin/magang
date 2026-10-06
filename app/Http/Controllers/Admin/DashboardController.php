<?php
// Simpan file ini di: app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users'      => DB::table('users')->count(),
            'instruktur' => DB::table('instruktur')->count(),
            'materi'     => DB::table('materi')->count(),
            'soal'       => DB::table('soal')->count(),
            'penilaian'  => DB::table('penilaian')->count(),
            'progress'   => DB::table('progress')->count(),
            'video'      => DB::table('video')->count(),
            'pdf'        => DB::table('pdf')->count(),
            'gambar'     => DB::table('gambar')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}