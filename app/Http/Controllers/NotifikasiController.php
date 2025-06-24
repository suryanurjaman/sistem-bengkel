<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotifikasiPelanggan;

class NotifikasiController extends Controller
{
    public function markAllRead(Request $request)
    {
        NotifikasiPelanggan::where('pelanggan_id', auth('pelanggan')->id())
            ->update(['is_read' => true]);

        return back();
    }

    public function clearAll(Request $request)
    {
        NotifikasiPelanggan::where('pelanggan_id', auth('pelanggan')->id())->delete();

        return back();
    }
}
