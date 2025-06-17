<?php

namespace App\Http\Controllers;

use App\Models\KategoriLayanan;
use App\Models\Layanan;
use App\Models\PemesananServis;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create()
    {
        $kategoris = KategoriLayanan::with('layanans')->get();
        return view('booking.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'plat_nomor'   => 'required|string|max:20',
            'jenis_motor'  => 'required|string|max:100',
            'layanans'     => 'required|array|min:1',
            'layanans.*'   => 'exists:layanan,id',
            'tanggal'      => 'required|date|after_or_equal:today',
            'keterangan'   => 'nullable|string',
        ]);

        $layanans = Layanan::whereIn('id', $data['layanans'])->get();
        $min = $layanans->sum('harga_min');
        $max = $layanans->sum('harga_max');

        $pesan = PemesananServis::create([
            'pelanggan_id'    => $data['pelanggan_id'],
            'plat_nomor'      => $data['plat_nomor'],
            'jenis_motor'     => $data['jenis_motor'],
            'status_id'       => 1,
            'tanggal_dipesan' => now(), // ⏳ waktu dibuatnya booking
            'tanggal_servis' => $data['tanggal'], // 📅 pilihan user
            'keterangan'      => $data['keterangan'] ?? null,
            'total_harga_min' => $min,
            'total_harga_max' => $max,
            'total_harga'     => $min,
        ]);

        $pesan->layanans()->attach($data['layanans']);
        $pesan->load('statusServis');

        session()->flash('booking_success', [
            'kode'    => $pesan->kode_booking,
            'tanggal_dipesan' => $pesan->tanggal_dipesan->format('d-m-Y'),
            'tanggal_servis'  => $pesan->tanggal_servis->format('d-m-Y'),
            'status'  => $pesan->statusServis->nama_status ?? 'Menunggu',
            'min'     => $pesan->total_harga_min,
            'max'     => $pesan->total_harga_max,
            'total'   => $pesan->total_harga,
        ]);

        return redirect()->route('booking.create');
    }
}
