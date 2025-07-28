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

        // Ambil semua query
        $filters = $request->query('tableFilters', []);

        // === Filter: Status Servis ===
        $statusId = data_get($filters, 'status_id.value');
        if (!empty($statusId)) {
            $query->where('status_id', $statusId);
        }

        // === Filter: Tanggal Servis ===
        $from = data_get($filters, 'tanggal_servis.from');
        $until = data_get($filters, 'tanggal_servis.until');

        if (!empty($from) && !empty($until)) {
            $query->whereBetween('tanggal_servis', [
                \Carbon\Carbon::parse($from)->startOfDay(),
                \Carbon\Carbon::parse($until)->endOfDay(),
            ]);
        }

        $data = $query->get();

        // Kirim juga $filters ke PDF view biar bisa ditampilkan infonya
        $pdf = Pdf::loadView('pdf.rekap-servis', compact('data', 'filters'));
        return $pdf->download('rekap-servis-bengkel.pdf');
    }

    // app/Http/Controllers/RekapController.php

    public function exportSingleInvoice(\App\Models\PemesananServis $pemesananServis)
    {
        $pemesananServis->load(['pelanggan', 'statusServis', 'layanans', 'barangServis']);

        return \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoiceAdmin', [
            'booking' => $pemesananServis
        ])->download('invoice-' . $pemesananServis->kode_booking . '.pdf');
    }
}
