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
    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white border border-green-200 rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold text-green-800 mb-1">🔍 Cek Status Servis Kendaraan</h2>
        <p class="text-sm text-gray-600 mb-5">Masukkan kode booking Anda di bawah ini.</p>

        <form id="statusForm" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" id="bookingCode"
                    class="w-full border border-gray-300 rounded-md py-2 pl-10 pr-4 text-sm focus:ring-green-500 focus:border-green-500"
                    placeholder="Masukkan Kode Booking" required>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-green-600">
                    <i class="fas fa-clipboard-check"></i>
                </div>
            </div>
            <button type="button" id="checkStatusButton"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm transition">
                Cek Status
            </button>
        </form>

        <div id="result" class="mt-6"></div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('checkStatusButton').addEventListener('click', function() {
            const bookingCode = document.getElementById('bookingCode').value.trim();
            const resultDiv = document.getElementById('result');

            if (!bookingCode) {
                resultDiv.innerHTML = '<p class="text-red-600 mt-3">Silakan masukkan kode booking.</p>';
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
<div class="mt-6 border border-green-300 rounded-lg bg-green-50 overflow-hidden">
  <div class="px-6 py-4 bg-green-100">
    <h3 class="text-lg font-bold text-green-800">🛠️ Informasi Status Servis</h3>
    <p class="text-sm text-green-700">Detail pemesanan dan status servis kendaraan Anda</p>
  </div>
  <dl class="divide-y divide-green-200 text-sm text-gray-700">
    ${infoRow("Nama Pelanggan", data.pelanggan)}
    ${infoRow("Kode Booking", data.kode)}
    ${infoRow("Tanggal Dipesan", data.tanggal)}
    ${infoRow("Tanggal Servis", data.tanggal_servis ?? '-')}
    ${infoRow("Plat Nomor", data.plat_nomor)}
    ${infoRow("Jenis Motor", data.jenis_motor)}
    ${infoRow("Alamat", data.alamat ?? '-')}
    ${infoRow("Status", `<span class="inline-block px-2 py-1 text-white rounded ${getStatusColor(data.status)}">${data.status}</span>`)}
    ${infoRow("Keterangan Status", data.status_keterangan)}
    ${infoRow("Layanan Dipilih", `
            <ul class="list-disc ml-5 space-y-1 text-gray-800">
              ${data.layanans.map(item => `<li>${item}</li>`).join('')}
            </ul>`)}
    ${infoRow("Total Harga Final", data.total_harga)}
    ${infoRow("Catatan Pelanggan", data.keterangan)}
    ${infoRow("Catatan Admin", data.keterangan_admin)}
    ${infoRow("Catatan Mekanik", data.catatan_mekanik ?? '-')}
  </dl>
</div>
`;
                    } else {
                        resultDiv.innerHTML = `<p class="text-red-600 mt-3">${data.message}</p>`;
                    }
                })
                .catch(() => {
                    resultDiv.innerHTML =
                        '<p class="text-red-600 mt-3">Terjadi kesalahan. Silakan coba lagi.</p>';
                });
        });

        function getStatusColor(status) {
            switch (status.toLowerCase()) {
                case 'menunggu':
                    return 'bg-gray-500';
                case 'diproses':
                    return 'bg-yellow-500';
                case 'selesai':
                    return 'bg-green-600';
                case 'dibatalkan':
                    return 'bg-red-600';
                default:
                    return 'bg-gray-400';
            }
        }

        function infoRow(label, value) {
            return `
<div class="px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
    <dt class="font-medium text-gray-900">${label}</dt>
    <dd class="mt-1 sm:mt-0 sm:col-span-2">${value}</dd>
</div>`;
        }
    </script>
@endpush
