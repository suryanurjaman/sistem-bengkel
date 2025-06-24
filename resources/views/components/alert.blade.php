@if (session('alert'))
    <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms
        class="fixed top-5 right-5 bg-green-500 text-white px-4 py-3 rounded shadow-lg flex items-start justify-between gap-3 z-50 w-80">
        <div class="flex-1">
            <strong class="block font-semibold">Sukses!</strong>
            <span class="text-sm">{{ session('alert') }}</span>
        </div>
        <button @click="show = false" class="text-white hover:text-gray-200 text-lg leading-none">&times;</button>
    </div>
@endif
