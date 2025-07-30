<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Rekap Servis Bengkel</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #888;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #004085;
            color: #fff;
        }

        .sub-info {
            background-color: #f9f9f9;
            padding-left: 20px;
            font-size: 11px;
        }

        ul {
            margin: 5px 0;
            padding-left: 20px;
        }

        tfoot td {
            font-size: 10px;
            text-align: right;
            padding-top: 10px;
            border: none;
        }
    </style>
</head>

<body>
    <h2>Rekap Servis Bengkel</h2>
    
    @php
        // Baris ini dihapus karena $filters sudah dikirim dari controller
        // $filters = request('tableFilters') ?? [];

        // Langsung gunakan variabel $filters yang sudah ada
        $from = $filters['tanggal_servis']['from'] ?? null;
        $until = $filters['tanggal_servis']['until'] ?? null;
        $status = $filters['status_id']['value'] ?? null;
        $statusText = $status ? \App\Models\StatusServis::find($status)?->nama_status : 'Semua';
    @endphp

    <p>
        <strong>Periode:</strong>
        {{ $from ? \Carbon\Carbon::parse($from)->translatedFormat('d F Y') : '-' }} -
        {{ $until ? \Carbon\Carbon::parse($until)->translatedFormat('d F Y') : '-' }}<br>
        <strong>Status Servis:</strong> {{ $statusText }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Kode Booking</th>
                <th>Nama Pelanggan</th>
                <th>Plat Nomor</th>
                <th>Jenis Motor</th>
                <th>Tanggal Servis</th>
                <th>Status</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->kode_booking }}</td>
                    <td>{{ $item->pelanggan->nama_lengkap }}</td>
                    <td>{{ $item->plat_nomor }}</td>
                    <td>{{ $item->jenis_motor }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_servis)->translatedFormat('d F Y') }}</td>
                    <td>{{ $item->statusServis->nama_status }}</td>
                    <td>Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="7" class="sub-info">
                        <strong>Layanan:</strong>
                        <ul>
                            @forelse ($item->layanans as $layanan)
                                <li>{{ $layanan->nama_layanan }} -
                                    Rp{{ number_format($layanan->harga_jasa ?? 0, 0, ',', '.') }}</li>
                            @empty

                                <li><em>Tidak ada layanan</em></li>
                            @endforelse
                        </ul>

                        <strong>Barang:</strong>
                        <ul>
                            @forelse ($item->barangServis as $barang)
                                <li>{{ $barang->nama_barang }} - {{ $barang->pivot->jumlah ?? 1 }} pcs</li>
                            @empty
                                <li><em>Tidak ada barang</em></li>
                            @endforelse
                        </ul>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6" style="text-align: right;"><strong>Total Pendapatan:</strong></td>
                <td><strong>Rp{{ number_format($data->sum('total_harga'), 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <br>
    <table>
        <tfoot>
            <tr>
                <td colspan="7">Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
