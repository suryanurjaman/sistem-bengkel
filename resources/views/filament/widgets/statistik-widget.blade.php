<x-filament-widgets::widget>
    <x-filament::card>
        @php
            // Panggil getData() untuk mengambil array statistik
            $stats = $this->getData();
        @endphp

        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 bg-gray-100 rounded-lg">
                <h3 class="text-sm text-gray-600">Total Admin</h3>
                <p class="text-2xl font-bold">{{ $stats['totalAdmin'] }}</p>
            </div>
            <div class="p-4 bg-gray-100 rounded-lg">
                <h3 class="text-sm text-gray-600">Total Layanan</h3>
                <p class="text-2xl font-bold">{{ $stats['totalLayanan'] }}</p>
            </div>
            <div class="p-4 bg-gray-100 rounded-lg">
                <h3 class="text-sm text-gray-600">Total Pemesanan</h3>
                <p class="text-2xl font-bold">{{ $stats['totalPemesanan'] }}</p>
            </div>
            <div class="p-4 bg-gray-100 rounded-lg">
                <h3 class="text-sm text-gray-600">Pemesanan Selesai</h3>
                <p class="text-2xl font-bold">{{ $stats['pemesananSelesai'] }}</p>
            </div>
        </div>
    </x-filament::card>
</x-filament-widgets::widget>
