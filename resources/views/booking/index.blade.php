@extends('layouts.app')

@section('title', 'Daftar Booking Saya')

@push('styles')
    <style>
        .status-badge {
            @apply inline-block px-3 py-1 rounded-full text-xs font-semibold;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-6xl mx-auto py-10 px-4">
        <h1 class="text-2xl font-bold mb-6 text-center text-green-700">Daftar Booking Pelanggan</h1>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($bookings->isEmpty())
            <div class="text-center text-gray-600">
                <p class="mb-4">Belum ada bookingan.</p>
                <a href="{{ route('booking.create') }}"
                    class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Buat Booking Baru
                </a>
            </div>
        @else
            <div class="overflow-x-auto rounded-xl shadow">
                <table class="min-w-full bg-white text-sm rounded-xl overflow-hidden">
                    <thead class="bg-green-600 text-white">
                        <tr>
                            <th class="px-5 py-4 text-left">Kode</th>
                            <th class="px-5 py-4 text-center">Tanggal Servis</th>
                            <th class="px-5 py-4 text-center">Alamat</th>
                            <th class="px-5 py-4 text-center">Status</th>
                            <th class="px-5 py-4 text-center">Total Harga</th>
                            <th class="px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($bookings as $booking)
                            @php
                                $statusColor = match ($booking->status_id) {
                                    1 => 'bg-yellow-100 text-yellow-700',
                                    2 => 'bg-blue-100 text-blue-700',
                                    3 => 'bg-indigo-100 text-indigo-700',
                                    4 => 'bg-green-100 text-green-700',
                                    5 => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp

                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4 font-semibold text-gray-800">{{ $booking->kode_booking }}</td>

                                <td class="px-5 py-4 text-center text-gray-700">
                                    {{ $booking->tanggal_servis->format('d M Y') }}
                                </td>

                                <td class="px-5 py-4 text-center text-gray-600 max-w-xs truncate"
                                    title="{{ $booking->alamat }}">
                                    {{ \Illuminate\Support\Str::limit($booking->alamat, 40) }}
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <span
                                        class="inline-block px-3 py-1 rounded-full text-xs font-semibold shadow-sm {{ $statusColor }}">
                                        {{ $booking->statusServis->nama_status ?? 'Menunggu' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <div class="relative group inline-block">
                                        <span class="font-semibold text-green-700 cursor-pointer">
                                            Rp{{ number_format($booking->total_harga, 0, ',', '.') }}
                                        </span>
                                        <div
                                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max bg-white border border-gray-300 text-xs text-gray-700 rounded shadow-lg px-3 py-2 opacity-0 group-hover:opacity-100 transition z-20 whitespace-nowrap pointer-events-none">
                                            Jasa:
                                            Rp{{ number_format($booking->layanans->sum('harga_jasa'), 0, ',', '.') }}<br>
                                            Barang:
                                            Rp{{ number_format($booking->barangServis->sum('harga'), 0, ',', '.') }}
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center space-x-2">
                                    @if ($booking->status_id != 5)
                                        <a href="{{ route('booking.edit', $booking->id) }}"
                                            class="text-blue-600 hover:underline">Edit</a>

                                        <x-confirm-delete-modal :id="$booking->id" :action="route('booking.cancel', $booking->id)" method="PUT"
                                            button-label="Batalkan" modal-title="Batalkan Booking"
                                            modal-description="Booking ini akan dibatalkan dan tidak bisa diubah kembali."
                                            confirm-label="Ya, Batalkan"
                                            confirm-color="bg-green-500 hover:bg-green-600 text-white" />
                                    @else
                                        <x-confirm-delete-modal :id="$booking->id" :action="route('booking.hide', $booking->id)" method="POST"
                                            button-label="Hapus dari Daftar" modal-title="Hapus Booking?"
                                            modal-description="Booking akan disembunyikan dari daftar Anda."
                                            confirm-label="Ya, Sembunyikan"
                                            confirm-color="bg-red-600 hover:bg-red-700 text-white" />
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('booking.create') }}"
                    class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Tambah Booking Baru
                </a>
            </div>
        @endif
    </div>
@endsection
