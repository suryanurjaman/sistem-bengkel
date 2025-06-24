@extends('layouts.app')

@section('title', 'Booking Servis')

@section('content')
    <div class="bg-green-100 py-12">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">

            <div class="flex flex-row justify-between">
                <h1 class="text-2xl font-bold text-green-700 mb-4">🛠️ Booking Servis</h1>

                <div class="mb-4">
                    <a href="{{ route('booking.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                        ← Kembali ke Daftar Booking
                    </a>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-6">
                Silakan isi form berikut untuk melakukan booking servis di Bengkel Sahabat Motor Paijo.
            </p>

            @if (session()->has('booking_success'))
                <script>
                    window.bookingSuccess = @json(session('booking_success'));
                </script>
                <div x-cloak x-data="{ open: true, info: window.bookingSuccess || {} }" x-show="open"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div @click.away="open = false"
                        class="bg-white rounded-lg shadow-lg w-full max-w-md sm:max-w-lg md:max-w-xl p-6 mx-4">
                        <h2 class="text-xl font-bold mb-4 text-green-600">Booking Berhasil!</h2>
                        <ul class="space-y-2 text-gray-700 text-sm">
                            <li><strong>Kode:</strong> <span x-text="info.kode"></span></li>
                            <li><strong>Tanggal Servis:</strong> <span x-text="info.tanggal_servis"></span></li>
                            <li><strong>Booking Dibuat:</strong> <span x-text="info.tanggal_dipesan"></span></li>
                            <li><strong>Status:</strong> <span x-text="info.status"></span></li>
                            <li><strong>Harga Jasa:</strong> Rp<span x-text="Number(info.jasa).toLocaleString()"></span>
                            </li>
                            <li><strong>Harga Barang:</strong> Rp<span x-text="Number(info.barang).toLocaleString()"></span>
                            </li>
                            <li><strong>Total:</strong> Rp<span x-text="Number(info.total).toLocaleString()"></span></li>
                            <li>
                                <strong>Harga Estimasi:</strong>
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

            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>Silahkan perbaiki pengisian form:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="bookingForm" action="{{ route('booking.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="pelanggan_id" value="{{ auth('pelanggan')->id() }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Data Pelanggan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" readonly value="{{ auth('pelanggan')->user()->nama_lengkap ?? '' }}"
                            class="block w-full mt-1 rounded-lg bg-gray-100 text-gray-700 border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" readonly value="{{ auth('pelanggan')->user()->email ?? '' }}"
                            class="block w-full mt-1 rounded-lg bg-gray-100 text-gray-700 border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text" readonly value="{{ auth('pelanggan')->user()->no_hp ?? '' }}"
                            class="block w-full mt-1 rounded-lg bg-gray-100 text-gray-700 border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Servis</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal') }}"
                            class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Plat Nomor</label>
                        <input type="text" name="plat_nomor" value="{{ old('plat_nomor') }}"
                            class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Motor</label>
                        <input type="text" name="jenis_motor" value="{{ old('jenis_motor') }}"
                            class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500"
                            required>
                    </div>

                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3"
                            class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500" required>{{ old('alamat', $booking->alamat ?? '') }}</textarea>
                    </div>

                </div>

                {{-- Pilih Layanan dan Barang --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Layanan</label>
                    <div class="space-y-4">
                        @foreach ($kategoris as $kategori)
                            <details class="mb-4 border rounded-lg bg-white shadow-sm">
                                <summary
                                    class="cursor-pointer px-4 py-2 bg-green-100 hover:bg-green-200 font-medium text-green-800">
                                    {{ $kategori->nama_kategori }}
                                </summary>
                                <div class="px-4 py-3 space-y-2">
                                    @foreach ($kategori->layanans as $layanan)
                                        <div class="border rounded-md p-3 bg-gray-50 space-y-2">
                                            <div class="flex items-start gap-3">
                                                <input type="checkbox" name="layanans[]" value="{{ $layanan->id }}"
                                                    class="layanan-checkbox w-5 h-5 mt-1 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                                    data-id="{{ $layanan->id }}" data-jasa="{{ $layanan->harga_jasa }}"
                                                    onchange="toggleBarang(this)">
                                                <label class="text-sm text-gray-800">
                                                    <div class="font-medium">{{ $layanan->nama_layanan }}</div>
                                                    <div class="text-xs text-gray-500">Jasa:
                                                        Rp{{ number_format($layanan->harga_jasa) }}</div>
                                                </label>
                                            </div>

                                            {{-- Barang --}}
                                            <div class="ml-7 space-y-2 barang-wrapper" data-parent="{{ $layanan->id }}"
                                                style="display: none">
                                                @foreach ($layanan->barangServis as $barang)
                                                    <div class="flex items-start gap-3">
                                                        <input type="checkbox" name="barang_ids[]"
                                                            value="{{ $barang->id }}"
                                                            class="barang-checkbox w-4 h-4 mt-1 text-green-500"
                                                            data-harga="{{ $barang->harga }}">
                                                        <label class="text-sm text-gray-700">
                                                            {{ $barang->nama_barang }} -
                                                            Rp{{ number_format($barang->harga) }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                    <textarea name="keterangan" rows="4"
                        class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">{{ old('keterangan') }}</textarea>
                </div>

                {{-- Total Harga --}}
                <div class="text-sm text-gray-700">
                    <strong>Total Harga:</strong> <span id="total_harga">Rp0</span>
                </div>

                <div class="text-right">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 transition">
                        🚀 Kirim Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleBarang(cb) {
            const layananId = cb.dataset.id;
            const barangWrapper = document.querySelector(`.barang-wrapper[data-parent='${layananId}']`);
            barangWrapper.style.display = cb.checked ? 'block' : 'none';
            updateTotal();
        }

        function updateTotal() {
            let total = 0;

            document.querySelectorAll('.layanan-checkbox:checked').forEach(cb => {
                total += parseInt(cb.dataset.jasa || 0);
            });

            document.querySelectorAll('.barang-checkbox:checked').forEach(cb => {
                total += parseInt(cb.dataset.harga || 0);
            });

            document.getElementById('total_harga').textContent = 'Rp' + total.toLocaleString();
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.layanan-checkbox:checked').forEach(cb => toggleBarang(cb));
            document.querySelectorAll('.barang-checkbox').forEach(cb => cb.addEventListener('change', updateTotal));

            // Tambahan untuk hanya 1 barang per layanan
            document.querySelectorAll('.barang-wrapper').forEach(wrapper => {
                wrapper.querySelectorAll('.barang-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        if (this.checked) {
                            wrapper.querySelectorAll('.barang-checkbox').forEach(cb => {
                                if (cb !== this) cb.checked = false;
                            });
                        }
                        updateTotal();
                    });
                });
            });
        });
    </script>
@endsection
