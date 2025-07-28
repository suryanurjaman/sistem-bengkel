<?php

namespace App\Http\Controllers;

use App\Models\BarangServis;
use App\Models\KategoriLayanan;
use App\Models\Layanan;
use App\Models\PemesananServis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;


class BookingController extends Controller
{
    public function index()
    {
        $bookings = PemesananServis::with('statusServis')
            ->where('pelanggan_id', Auth::guard('pelanggan')->id())
            ->where('is_hidden', false)
            ->latest()
            ->get();

        return view('booking.index', compact('bookings'));
    }

    public function create()
    {
        $kategoris = KategoriLayanan::with(['layanans.barangServis'])->get();

        $jenisMotorList = Layanan::select('tipe_motor')
            ->distinct()
            ->whereNotNull('tipe_motor')
            ->pluck('tipe_motor');

        return view('booking.create', compact('kategoris', 'jenisMotorList'));
    }

    public function downloadInvoice($kode)
    {
        $booking = PemesananServis::where('kode_booking', $kode)->with(['pelanggan', 'layanans', 'barangServis'])->firstOrFail();

        $pdf = Pdf::loadView('pdf.invoice', compact('booking'));
        return $pdf->download("Invoice-{$booking->kode_booking}.pdf");
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'plat_nomor'   => 'required|string|max:20',
            'tipe_motor'  => 'required|string|max:100',
            'alamat'       => 'required|string|max:255',
            'layanans'     => 'required|array|min:1',
            'layanans.*'   => 'exists:layanan,id',
            'barang_ids'   => 'nullable|array',
            'barang_ids.*' => 'exists:barang_servis,id',
            'tanggal'      => 'required|date|after_or_equal:today',
            'keterangan'   => 'nullable|string',
        ]);

        $layanans = Layanan::with('barangServis')->whereIn('id', $data['layanans'])->get();

        $totalHargaJasa   = $layanans->sum('harga_jasa');
        $barangList = BarangServis::whereIn('id', $data['barang_ids'] ?? [])->get();
        $totalHargaBarang = $barangList->sum('harga');
        $min = $layanans->sum('harga_min');
        $max = $layanans->sum('harga_max');
        $total = $totalHargaJasa + $totalHargaBarang;

        $pesan = PemesananServis::create([
            'pelanggan_id'    => $data['pelanggan_id'],
            'plat_nomor'      => $data['plat_nomor'],
            'jenis_motor'     => $data['tipe_motor'],
            'alamat'          => $data['alamat'],
            'status_id'       => 1,
            'tanggal_dipesan' => now(),
            'tanggal_servis'  => $data['tanggal'],
            'keterangan'      => $data['keterangan'] ?? null,
            'total_harga_min' => $min,
            'total_harga_max' => $max,
            'total_harga'     => $total,
        ]);

        $pesan->layanans()->attach($data['layanans']);

        if (isset($data['barang_ids'])) {
            $pesan->barangServis()->attach($data['barang_ids']);
        }

        session()->flash('booking_success', [
            'kode' => $pesan->kode_booking,
            'tanggal_dipesan' => $pesan->tanggal_dipesan->format('d-m-Y'),
            'tanggal_servis'  => $pesan->tanggal_servis->format('d-m-Y'),
            'status' => $pesan->statusServis->nama_status ?? 'Menunggu',
            'min' => $min,
            'max' => $max,
            'total' => $total,
            'jasa' => $totalHargaJasa,
            'barang' => $totalHargaBarang,
        ]);

        session()->flash('alert', 'Booking berhasil diajukan!');
        return redirect()->route('booking.create');
    }

    public function edit($id)
    {
        $booking = PemesananServis::with(['layanans', 'barangServis'])
            ->where('pelanggan_id', auth('pelanggan')->id())
            ->findOrFail($id);

        $kategoris = KategoriLayanan::with('layanans.barangServis')->get();

        return view('booking.edit', compact('booking', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $booking = PemesananServis::where('pelanggan_id', auth('pelanggan')->id())->findOrFail($id);

        $data = $request->validate([
            'plat_nomor'   => 'required|string|max:20',
            'tipe_motor'  => 'required|string|max:100',
            'alamat'       => 'required|string|max:255',
            'layanans'     => 'required|array|min:1',
            'layanans.*'   => 'exists:layanan,id',
            'barang_ids'   => 'nullable|array',
            'barang_ids.*' => 'exists:barang_servis,id',
            'tanggal'      => 'required|date|after_or_equal:today',
            'keterangan'   => 'nullable|string',
        ]);

        $layanans = Layanan::with('barangServis')->whereIn('id', $data['layanans'])->get();

        $totalHargaJasa   = $layanans->sum('harga_jasa');
        $barangList = BarangServis::whereIn('id', $data['barang_ids'] ?? [])->get();
        $totalHargaBarang = $barangList->sum('harga');
        $min = $layanans->sum('harga_min');
        $max = $layanans->sum('harga_max');
        $total = $totalHargaJasa + $totalHargaBarang;

        $booking->update([
            'plat_nomor'      => $data['plat_nomor'],
            'jenis_motor'     => $data['tipe_motor'],
            'alamat'          => $data['alamat'],
            'tanggal_servis'  => $data['tanggal'],
            'keterangan'      => $data['keterangan'],
            'total_harga_min' => $min,
            'total_harga_max' => $max,
            'total_harga'     => $total,
        ]);

        $booking->layanans()->sync($data['layanans']);
        $booking->barangServis()->sync($data['barang_ids'] ?? []);

        return redirect()->route('booking.index')->with('alert', 'Booking berhasil diperbarui!');
    }

    public function hide($id)
    {
        $booking = PemesananServis::where('pelanggan_id', auth('pelanggan')->id())
            ->where('status_id', 5)
            ->findOrFail($id);

        $booking->update(['is_hidden' => true]);

        return redirect()->route('booking.index')->with('alert', 'Booking berhasil dihapus dari daftar Anda.');
    }



    public function cancel($id)
    {
        $booking = PemesananServis::where('pelanggan_id', auth('pelanggan')->id())
            ->where('status_id', '!=', 5)
            ->findOrFail($id);

        $booking->update([
            'status_id' => 5 // status "Dibatalkan"
        ]);

        return redirect()->route('booking.index')->with('alert', 'Booking berhasil dibatalkan.');
    }
}
