<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriLayanan;

class BerandaController extends Controller
{
    public function index()
    {
        // Ambil semua kategori beserta 3 layanan terkait
        $kategoriLayanan = KategoriLayanan::all(); // tidak pakai relasi

        return view('beranda', compact('kategoriLayanan'));
    }
}
