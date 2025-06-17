<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriLayanan;

class KategoriLayananController extends Controller
{
    public function index()
    {
        $kategoris = KategoriLayanan::all(); // ambil semua kategori
        return view('layanan', compact('kategoris'));
    }
}
