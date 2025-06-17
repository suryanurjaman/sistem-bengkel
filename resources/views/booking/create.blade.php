@extends('layouts.app')

@section('title', 'Booking Servis')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/.../fontawesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/Booking Servis.css') }}">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="booking-wrapper">
        <!-- keterangan informasi bengkel -->
        <div class="booking-section">
            <h1 class="booking-title"><b>Booking Servis</b></h1>
            <p><i>Berikut form booking servis untuk melakukan booking servis di Bengkel Sahabat Motor Paijo.</i></p>
            <div class="garis-hr"></div>
        </div>

        @if (session()->has('booking_success'))
            <div x-cloak x-data="{ open: true, info: window.bookingSuccess || {} }" x-show="open"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div @click.away="open = false"
                    class="bg-white rounded-lg shadow-lg w-full max-w-md sm:max-w-lg md:max-w-xl p-6 mx-4">
                    <h2 class="text-xl font-bold mb-4 text-green-600">Booking Berhasil!</h2>
                    <ul class="space-y-2 text-gray-700">
                        <li><strong>Kode:</strong> <span x-text="info.kode"></span></li>
                        <li><strong>Tanggal Servis:</strong> <span x-text="info.tanggal_servis"></span></li>
                        <li><strong>Booking Dibuat:</strong> <span x-text="info.tanggal_dipesan"></span></li>
                        <li><strong>Status:</strong> <span x-text="info.status"></span></li>
                        <li>
                            <strong>Harga Range:</strong>
                            Rp<span x-text="Number(info.min).toLocaleString()"></span>
                            – Rp<span x-text="Number(info.max).toLocaleString()"></span>
                        </li>
                    </ul>
                    <div class="mt-6 text-right">
                        <button @click="open = false; window.location='{{ route('home') }}'"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <div class="form">
            <h2>Form Booking Servis</h2>

            @if ($errors->any())
                <div class="alert">
                    <ul>
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="bookingForm" action="{{ route('booking.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="pelanggan_id" value="{{ auth('pelanggan')->id() }}">

                {{-- Grid 2 kolom --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama --}}
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" id="nama" readonly
                            value="{{ auth('pelanggan')->user()->nama_lengkap ?? '' }}"
                            class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm bg-gray-100 text-gray-700 cursor-not-allowed">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" readonly value="{{ auth('pelanggan')->user()->email ?? '' }}"
                            class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm bg-gray-100 text-gray-700 cursor-not-allowed">
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text" id="telepon" readonly value="{{ auth('pelanggan')->user()->no_hp ?? '' }}"
                            class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm bg-gray-100 text-gray-700 cursor-not-allowed">
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Servis</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}"
                            min="{{ date('Y-m-d') }}"
                            class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500"
                            required>
                    </div>

                    {{-- Plat Nomor --}}
                    <div>
                        <label for="plat_nomor" class="block text-sm font-medium text-gray-700">Plat Nomor</label>
                        <input type="text" name="plat_nomor" id="plat_nomor" value="{{ old('plat_nomor') }}"
                            class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500"
                            required>
                    </div>

                    {{-- Jenis Motor --}}
                    <div>
                        <label for="jenis_motor" class="block text-sm font-medium text-gray-700">Jenis Motor</label>
                        <input type="text" name="jenis_motor" id="jenis_motor" value="{{ old('jenis_motor') }}"
                            class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500"
                            required>
                    </div>
                </div>

                {{-- Pilih Layanan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Layanan</label>

                    {{-- Pesan error layanan (akan ditampilkan via JS) --}}
                    <p id="layanan-error" class="text-sm text-red-600 font-medium mb-2 hidden">
                        Silakan pilih minimal satu layanan.
                    </p>

                    <div class="space-y-4" id="layanans-wrapper">
                        @foreach ($kategoris as $kategori)
                            <details class="mb-4 border rounded-lg bg-white shadow-sm">

                                <summary
                                    class="cursor-pointer px-4 py-2 bg-green-100 hover:bg-green-200 font-medium text-green-800">
                                    {{ $kategori->nama_kategori }}
                                </summary>
                                <div class="px-4 py-3 space-y-2">
                                    @foreach ($kategori->layanans as $layanan)
                                        <div class="flex items-start gap-3 p-3 border rounded-md">
                                            <input type="checkbox" name="layanans[]" value="{{ $layanan->id }}"
                                                id="layanan_{{ $layanan->id }}" data-min="{{ $layanan->harga_min }}"
                                                data-max="{{ $layanan->harga_max }}" onchange="updateTotal()"
                                                class="mt-1 w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                                {{ in_array($layanan->id, old('layanans', [])) ? 'checked' : '' }}>
                                            <label for="layanan_{{ $layanan->id }}" class="text-sm text-gray-800">
                                                <div class="font-medium">{{ $layanan->nama_layanan }}</div>
                                                <div class="text-xs text-gray-500">
                                                    Rp{{ number_format($layanan->harga_min, 0, ',', '.') }} -
                                                    Rp{{ number_format($layanan->harga_max, 0, ',', '.') }}
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>

                {{-- Catatan --}}
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                    <textarea name="keterangan" id="keterangan" rows="4"
                        class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">{{ old('keterangan') }}</textarea>
                </div>

                {{-- Total Harga --}}
                <div class="text-sm text-gray-700">
                    <strong>Total Harga:</strong>
                    <span id="total_min">Rp0</span> – <span id="total_max">Rp0</span>
                </div>

                {{-- Submit --}}
                <div class="text-right">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 transition">
                        Kirim Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@if (session()->has('booking_success'))
    <script>
        window.bookingSuccess = @json(session('booking_success'));
    </script>
@endif

@push('scripts')
    <script>
        function loadLayanan() {
            const kategoriId = document.getElementById('kategori').value;
            if (!kategoriId) {
                document.getElementById('layanans-wrapper').innerHTML = '';
                updateTotal();
                return;
            }
            fetch(`/api/kategori/${kategoriId}/layanans`)
                .then(res => res.json())
                .then(data => {
                    const wrapper = document.getElementById('layanans-wrapper');
                    wrapper.innerHTML = '';
                    data.forEach(l => {
                        const id = `layanan_${l.id}`;
                        wrapper.insertAdjacentHTML('beforeend', `
    <div class="flex items-center gap-3 p-3 bg-white border rounded shadow-sm">
        <input type="checkbox" name="layanans[]" value="${l.id}"
               id="${id}" data-min="${l.harga_min}" data-max="${l.harga_max}"
               onchange="updateTotal()"
               class="w-5 h-5 ml-4 mr-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
               ${ (Array.isArray(@json(old('layanans', []))) && @json(old('layanans', [])).includes(l.id)) ? 'checked' : '' }
        >
        <label for="${id}" class="text-sm pl-4 text-gray-800">
            <span class="font-medium block">${l.nama_layanan}</span>
            <span class="text-xs text-gray-500">Rp${Number(l.harga_min).toLocaleString()} - Rp${Number(l.harga_max).toLocaleString()}</span>
        </label>
    </div>
`);
                    });
                    updateTotal();
                });
        }

        function updateTotal() {
            let min = 0,
                max = 0;
            document.querySelectorAll('#layanans-wrapper input[type="checkbox"]:checked').forEach(cb => {
                min += parseFloat(cb.dataset.min);
                max += parseFloat(cb.dataset.max);
            });
            document.getElementById('total_min').textContent = `Rp${min.toLocaleString()}`;
            document.getElementById('total_max').textContent = `Rp${max.toLocaleString()}`;
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Jika ada old('kategori_id'), load langsung
            @if (old('kategori_id'))
                loadLayanan();
            @endif
        });

        function validateLayanan() {
            const checked = document.querySelectorAll('#layanans-wrapper input[type="checkbox"]:checked');
            const errorElement = document.getElementById('layanan-error');

            if (checked.length === 0) {
                errorElement.classList.remove('hidden');
            } else {
                errorElement.classList.add('hidden');
            }
        }

        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            const checked = document.querySelectorAll('#layanans-wrapper input[type="checkbox"]:checked');
            const errorElement = document.getElementById('layanan-error');

            if (checked.length === 0) {
                e.preventDefault();
                errorElement.classList.remove('hidden');
                // Optional: scroll ke bagian error
                errorElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    </script>
@endpush
