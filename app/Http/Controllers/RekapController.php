<?php

namespace App\Http\Controllers;

use App\Models\PemesananServis;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function exportPdf(Request $request)
    {
        $query = PemesananServis::with([
            'pelanggan',
            'statusServis',
            'layanans',
            'barangServis',
        ]);

        // Filter by status jika ada
        if ($request->has('tableFilters.status_id.value')) {
            $statusId = $request->input('tableFilters')['status_id']['value'];
            $query->where('status_id', $statusId);
        }

        $data = $query->get();

        $pdf = Pdf::loadView('pdf.rekap-servis', compact('data'));
        return $pdf->download('rekap-servis-bengkel.pdf');
    }
}
