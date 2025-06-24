@extends('layouts.app')

@section('title', 'Edit Booking Servis')

@section('content')
    <div class="bg-green-100 py-12">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <div class="flex flex-row justify-between">
                <h2 class="text-2xl font-bold mb-6 text-green-700">✏️ Edit Booking Servis</h2>
                <div class="mb-4">
                    <a href="{{ route('booking.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                        ← Kembali ke Daftar Booking
                    </a>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>Ada error:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ route('booking.update', $booking->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Plat Nomor</label>
                        <input type="text" name="plat_nomor" value="{{ old('plat_nomor', $booking->plat_nomor) }}"
                            class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Motor</label>
                        <input type="text" name="jenis_motor" value="{{ old('jenis_motor', $booking->jenis_motor) }}"
                            class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Servis</label>
                        <input type="date" name="tanggal"
                            value="{{ old('tanggal', $booking->tanggal_servis->format('Y-m-d')) }}"
                            class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500"
                            required>
                    </div>

                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3"
                            class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500" required>{{ old('alamat', $booking->alamat ?? '') }}</textarea>
                    </div>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                    <textarea name="keterangan" rows="4"
                        class="block w-full mt-1 rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">{{ old('keterangan', $booking->keterangan) }}</textarea>
                </div>

                {{-- Pilih Layanan & Barang --}}
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
                                                    onchange="toggleBarang(this)"
                                                    {{ in_array($layanan->id, old('layanans', $booking->layanans->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                <label class="text-sm text-gray-800">
                                                    <div class="font-medium">{{ $layanan->nama_layanan }}</div>
                                                    <div class="text-xs text-gray-500">Jasa:
                                                        Rp{{ number_format($layanan->harga_jasa) }}</div>
                                                </label>
                                            </div>

                                            <div class="ml-7 space-y-2 barang-wrapper" data-parent="{{ $layanan->id }}"
                                                style="display: none">
                                                @foreach ($layanan->barangServis as $barang)
                                                    <div class="flex items-start gap-3">
                                                        <input type="checkbox" name="barang_ids[]"
                                                            value="{{ $barang->id }}"
                                                            class="barang-checkbox w-4 h-4 mt-1 text-green-500"
                                                            data-harga="{{ $barang->harga }}"
                                                            {{ in_array($barang->id, old('barang_ids', $booking->barangServis->pluck('id')->toArray())) ? 'checked' : '' }}>
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

                {{-- Total Harga --}}
                <div class="text-sm text-gray-700">
                    <strong>Total Harga:</strong> <span id="total_harga">Rp0</span>
                </div>

                <div class="text-right">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 transition">
                        💾 Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleBarang(cb) {
            const layananId = cb.dataset.id;
            const wrapper = document.querySelector(`.barang-wrapper[data-parent='${layananId}']`);
            wrapper.style.display = cb.checked ? 'block' : 'none';
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
            const layananCheckboxes = document.querySelectorAll('.layanan-checkbox');
            const barangCheckboxes = document.querySelectorAll('.barang-checkbox');

            layananCheckboxes.forEach(cb => {
                const layananId = cb.dataset.id;
                const wrapper = document.querySelector(`.barang-wrapper[data-parent='${layananId}']`);

                // Cek apakah checkbox ini sudah dicentang (berdasarkan old() atau dari DB)
                if (cb.checked) {
                    // Buka detail induknya
                    const details = cb.closest('details');
                    if (details) details.open = true;

                    // Tampilkan barang terkait
                    if (wrapper) wrapper.style.display = 'block';
                } else {
                    if (wrapper) wrapper.style.display = 'none';
                }

                // Tambahkan listener untuk toggle barang saat user klik layanan
                cb.addEventListener('change', function() {
                    wrapper.style.display = cb.checked ? 'block' : 'none';
                    updateTotal();
                });
            });

            // Tambahkan listener untuk update total saat barang dipilih
            barangCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateTotal);
            });

            // Hanya satu barang per layanan
            document.querySelectorAll('.barang-wrapper').forEach(wrapper => {
                const checkboxes = wrapper.querySelectorAll('.barang-checkbox');
                checkboxes.forEach(cb => {
                    cb.addEventListener('change', function() {
                        if (this.checked) {
                            checkboxes.forEach(other => {
                                if (other !== this) other.checked = false;
                            });
                        }
                        updateTotal();
                    });
                });
            });

            updateTotal(); // Hitung total di awal
        });
    </script>
@endsection
