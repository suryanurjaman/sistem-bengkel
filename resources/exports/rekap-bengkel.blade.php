<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekap Servis Bengkel</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #aaa;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>
    <h2>Rekap Data Servis Bengkel</h2>
    <table>
        <thead>
            <tr>
                <th>Kode Booking</th>
                <th>Nama</th>
                <th>Jenis Motor</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->kode_booking }}</td>
                    <td>{{ $item->pelanggan->nama_lengkap }}</td>
                    <td>{{ $item->jenis_motor }}</td>
                    <td>{{ $item->tanggal_servis }}</td>
                    <td>{{ $item->statusServis->nama_status }}</td>
                    <td>Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
