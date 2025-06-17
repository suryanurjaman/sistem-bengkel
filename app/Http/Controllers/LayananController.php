<?php

namespace App\Http\Controllers;

use App\Models\KategoriLayanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function byKategori($slug)
    {
        // Cari kategori berdasarkan slug nama
        $kategori = KategoriLayanan::whereRaw("LOWER(REPLACE(nama_kategori, ' ', '-')) = ?", [strtolower($slug)])
                        ->firstOrFail();

        // Ambil layanan terkait jika ada relasi
        $layanans = $kategori->layanans;

        return view('list-layanan', compact('kategori', 'layanans'));
    }
}
