<div class="space-y-6">
    <div>
        <h3 class="font-semibold">Harga Layanan</h3>
        <ul class="list-disc pl-5">
            @forelse ($layanans as $layanan)
                <li>{{ $layanan->nama_layanan }} (Rp {{ number_format($layanan->harga_jasa, 0, ',', '.') }})</li>
            @empty
                <li>Belum ada layanan dipilih.</li>
            @endforelse
        </ul>
        <p class="font-bold mt-2">Total Layanan: Rp {{ number_format($layanans->sum('harga_jasa'), 0, ',', '.') }}</p>
    </div>

    <div>
        <h3 class="font-semibold">Harga Barang</h3>
        <ul class="list-disc pl-5">
            @forelse ($barangList as $barang)
                <li>{{ $barang->nama_barang }} (Rp {{ number_format($barang->harga, 0, ',', '.') }})</li>
            @empty
                <li>Belum ada barang dipilih.</li>
            @endforelse
        </ul>
        <p class="font-bold mt-2">Total Barang: Rp {{ number_format($barangList->sum('harga'), 0, ',', '.') }}</p>
    </div>

    <div class="border-t pt-4">
        <p class="text-lg font-bold">
            Total Keseluruhan: Rp
            {{ number_format($barangList->sum('harga') + $layanans->sum('harga_jasa'), 0, ',', '.') }}
        </p>
    </div>
</div>
