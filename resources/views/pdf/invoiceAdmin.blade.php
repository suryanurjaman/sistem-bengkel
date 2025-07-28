<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $booking->kode_booking }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 40px;
            background-color: #fff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }

        .logo {
            height: 60px;
        }

        .company-info {
            text-align: right;
        }

        h1 {
            font-size: 24px;
            color: #4CAF50;
            margin-top: 40px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        .section {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #f0f0f0;
            text-align: left;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .total {
            text-align: right;
            font-weight: bold;
            font-size: 16px;
            margin-top: 20px;
        }

        .footer {
            margin-top: 50px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header">
        <div class="company-info">
            <strong>Bengkel Sahabat Motor Paijo</strong><br>
            Jl. Cinangka No.199, Pasirwangi, Bandung<br>
            Telepon: +62 85659881426<br>
        </div>
    </div>

    <h1>INVOICE SERVIS</h1>

    {{-- Informasi Booking --}}
    <div class="section">
        <table>
            <tr>
                <th>Kode Booking</th>
                <td>{{ $booking->kode_booking }}</td>
                <th>Tanggal Servis</th>
                <td>{{ $booking->tanggal_servis->format('d M Y') }}</td>
            </tr>
            <tr>
                <th>Nama Pelanggan</th>
                <td>{{ $booking->pelanggan->nama_lengkap }}</td>
                <th>Nomor HP</th>
                <td>{{ $booking->pelanggan->no_hp }}</td>
            </tr>
            <tr>
                <th>Plat Nomor</th>
                <td>{{ $booking->plat_nomor }}</td>
                <th>Jenis Motor</th>
                <td>{{ ucfirst($booking->jenis_motor) }}</td>
            </tr>
        </table>
    </div>

    {{-- Layanan --}}
    <div class="section">
        <h3>Detail Layanan</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Layanan</th>
                    <th>Harga Jasa</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($booking->layanans as $layanan)
                    <tr>
                        <td>{{ $layanan->nama_layanan }}</td>
                        <td>Rp{{ number_format($layanan->harga_jasa, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Barang --}}
    <div class="section">
        <h3>Barang Digunakan</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga Barang</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($booking->barangServis as $barang)
                    <tr>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>Rp{{ number_format($barang->harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Total --}}
    <div class="total">
        Total Biaya: Rp{{ number_format($booking->total_harga, 0, ',', '.') }}
    </div>

    {{-- Footer --}}
    <div class="footer">
        Terima kasih telah melakukan servis di Bengkel Sahabat Motor Paijo.<br>
        Silakan hubungi kami jika ada pertanyaan atau keluhan.
    </div>
</body>

</html>
