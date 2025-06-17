@extends('layouts.app')

@section('title', 'Status Servis')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/Status servis.css') }}">
@endpush

@php
    $notifBookings = auth('pelanggan')->check()
        ? \App\Models\PemesananServis::where('pelanggan_id', auth('pelanggan')->id())
            ->latest()
            ->with('statusServis')
            ->get()
        : collect();
@endphp

@section('content')
    <!-- keterangan informasi bengkel -->
    <div class="status-section">
        <h1 class="status-title"><b>Status Servis</b></h1>
        <p><i>Cek status kendaraan Anda di sini dengan memasukkan kode booking yang telah diberikan.<br>
                Percayakan perawatan motor Anda pada Bengkel Sahabat Motor Paijo, solusi terpercaya untuk kendaraan Anda.
            </i></p>
        <div class="garis-hr"></div>
    </div>

    <!-- form status servis -->
    <div class="status-container">
        <div class="status-header">
            <h1>Cek Status Servis Kendaraan</h1>
            <p>Masukkan kode booking Anda untuk mengetahui status servis kendaraan.</p>
        </div>
        <div class="status-form-container">
            <form id="statusForm">
                <div class="input-group">
                    <i class="fas fa-clipboard-check input-icon"></i>
                    <input type="text" id="bookingCode" class="input-field" placeholder="Masukkan Kode Booking" required>
                </div>
                <button type="button" id="checkStatusButton" class="button">
                    Cek Status
                </button>
            </form>
            <!-- Div untuk menampilkan hasil status -->
            <div id="result" class="result"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('checkStatusButton').addEventListener('click', function() {
            const bookingCode = document.getElementById('bookingCode').value.trim();
            const resultDiv = document.getElementById('result');

            if (!bookingCode) {
                resultDiv.innerHTML = '<p class="text-red-600">Silakan masukkan kode booking.</p>';
                return;
            }

            fetch("{{ route('status.servis.cek') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        kode_booking: bookingCode
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        resultDiv.innerHTML = `
    <div class="text-left bg-green-50 border border-green-200 rounded p-4 text-sm text-green-800">
        <p><strong>Nama Pelanggan:</strong> ${data.pelanggan}</p>
        <p><strong>Kode Booking:</strong> ${data.kode}</p>
        <p><strong>Plat Nomor:</strong> ${data.plat_nomor}</p>
        <p><strong>Jenis Motor:</strong> ${data.jenis_motor}</p>
        <p><strong>Layanan:</strong> ${data.layanans.join(', ')}</p>
        <p><strong>Status:</strong> ${data.status}</p>
        <p><strong>Keterangan Status:</strong> ${data.status_keterangan}</p>
        <p><strong>Tanggal Dipesan:</strong> ${data.tanggal}</p>
        <p><strong>Total Harga Final:</strong> ${data.total_harga}</p>
        <p><strong>Range Harga:</strong> ${data.total_min} – ${data.total_max}</p>
        <p><strong>Catatan Pelanggan:</strong> ${data.keterangan}</p>
        <p><strong>Catatan Admin:</strong> ${data.keterangan_admin}</p>
    </div>
`;
                    } else {
                        resultDiv.innerHTML = `<p class="text-red-600">${data.message}</p>`;
                    }
                })
                .catch(() => {
                    resultDiv.innerHTML = '<p class="text-red-600">Terjadi kesalahan. Silakan coba lagi.</p>';
                });
        });
    </script>
@endpush
