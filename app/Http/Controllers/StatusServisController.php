<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemesananServis;

class StatusServisController extends Controller
{
    public function index()
    {
        $notifBookings = auth('pelanggan')->check()
            ? PemesananServis::where('pelanggan_id', auth('pelanggan')->id())
            ->latest()
            ->with('statusServis')
            ->get()
            : collect();

        return view('status-servis', compact('notifBookings'));
    }

    public function cek(Request $request)
    {
        $kode = $request->input('kode_booking');

        $servis = PemesananServis::with(['statusServis', 'pelanggan', 'layanans'])
            ->where('kode_booking', $kode)
            ->first();

        if ($servis) {
            return response()->json([
                'success' => true,
                'kode' => $servis->kode_booking,
                'status' => $servis->statusServis->nama_status ?? '-',
                'status_keterangan' => $servis->statusServis->keterangan ?? '-',
                'tanggal' => \Carbon\Carbon::parse($servis->tanggal_dipesan)->format('d M Y'),
                'pelanggan' => $servis->pelanggan->nama_lengkap ?? '-',
                'plat_nomor' => $servis->plat_nomor ?? '-',
                'jenis_motor' => $servis->jenis_motor ?? '-',
                'total_harga' => $servis->total_harga ? 'Rp' . number_format($servis->total_harga, 0, ',', '.') : '-',
                'total_min' => 'Rp' . number_format($servis->total_harga_min ?? 0, 0, ',', '.'),
                'total_max' => 'Rp' . number_format($servis->total_harga_max ?? 0, 0, ',', '.'),
                'keterangan' => $servis->keterangan ?? '-',
                'keterangan_admin' => $servis->keterangan_admin ?? '-',
                'catatan_mekanik' => $servis->catatan_mekanik ?? '-',
                'alamat' => $servis->alamat ?? '-',
                'tanggal_servis' => optional($servis->tanggal_servis)->format('d M Y'),
                'layanans' => $servis->layanans->map(function ($layanan) {
                    return $layanan->nama_layanan . ' (' .
                        'Rp' . number_format($layanan->harga_min, 0, ',', '.') .
                        ' - Rp' . number_format($layanan->harga_max, 0, ',', '.') . ')';
                })->toArray(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kode booking tidak ditemukan.',
        ]);
    }
}
