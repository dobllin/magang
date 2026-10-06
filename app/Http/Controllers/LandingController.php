<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil semua materi yang published, dengan info instruktur
        $materis = Materi::with('instruktur.user')
            ->where('status', 'published')
            ->latest()
            ->get();

        // Ambil kategori unik buat filter/navigation
        $kategoris = Materi::where('status', 'published')
            ->distinct()
            ->pluck('kategori');

        // Top courses (5 terbaru buat hero)
        $topCourses = Materi::with('instruktur.user')
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        return view('landing', compact('materis', 'kategoris', 'topCourses'));
    }
}