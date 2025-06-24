<x-filament::page>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Rekap Servis Bengkel</h1>
        <a href="{{ url()->current() }}/download" target="_blank"
            class="px-4 py-2 bg-amber-600 text-white rounded hover:bg-amber-700">
            Cetak PDF
        </a>
    </div>

    <table class="w-full text-sm text-left text-gray-500 border">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
            <tr>
                <th class="p-2">Kode Booking</th>
                <th class="p-2">Nama Pelanggan</th>
                <th class="p-2">Jenis Motor</th>
                <th class="p-2">Tanggal Servis</th>
                <th class="p-2">Status</th>
                <th class="p-2">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach (\App\Models\PemesananServis::with(['pelanggan', 'statusServis'])->get() as $item)
                <tr class="bg-white border-b">
                    <td class="p-2">{{ $item->kode_booking }}</td>
                    <td class="p-2">{{ $item->pelanggan->nama_lengkap }}</td>
                    <td class="p-2">{{ $item->jenis_motor }}</td>
                    <td class="p-2">{{ $item->tanggal_servis }}</td>
                    <td class="p-2">{{ $item->statusServis->nama_status }}</td>
                    <td class="p-2">Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-filament::page>
