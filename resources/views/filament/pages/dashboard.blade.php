<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-bold text-gray-700">Total Admin</h2>
            <p class="mt-2 text-3xl font-bold text-amber-500">{{ $totalAdmin }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-bold text-gray-700">Total Layanan</h2>
            <p class="mt-2 text-3xl font-bold text-amber-500">{{ $totalLayanan }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-bold text-gray-700">Total Pemesanan</h2>
            <p class="mt-2 text-3xl font-bold text-amber-500">{{ $totalPemesanan }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-bold text-gray-700">Pemesanan Selesai</h2>
            <p class="mt-2 text-3xl font-bold text-amber-500">{{ $pemesananSelesai }}</p>
        </div>
    </div>    
</x-filament::page>
