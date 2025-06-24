@php
    use Carbon\Carbon;
    use App\Models\NotifikasiPelanggan;

    $notifBookings = auth('pelanggan')->check()
        ? NotifikasiPelanggan::where('pelanggan_id', auth('pelanggan')->id())
            ->orderByDesc('created_at')
            ->take(10)
            ->get()
        : collect();

    $totalNotif = $notifBookings->where('is_read', false)->count();
@endphp

<div class="bg-[#00A86B] text-white px-5 py-1 flex justify-end items-center">
    <div class="flex items-center text-sm">
        <i class="fas fa-phone-alt mr-2"></i> +62 856‑5988‑1426
    </div>
</div>

<nav x-data="{ sidebarNotif: false, dropdown: false, open: false }" class="bg-white shadow-md px-5 py-3 flex justify-center gap-8 items-center relative">
    <a href="{{ route('home') }}">
        <img src="{{ asset('img/Logo Bengkel.png') }}" alt="Logo Bengkel" class="w-14 h-auto">
    </a>

    <div class="md:hidden cursor-pointer text-2xl" @click="open = !open">☰</div>

    <ul :class="open ? 'flex' : 'hidden'"
        class="flex-col md:flex md:flex-row gap-3 items-center text-sm font-bold md:gap-6 absolute md:static top-full left-0 w-full md:w-auto bg-white md:bg-transparent p-4 md:p-0 shadow-md md:shadow-none z-40">

        <li><a href="{{ route('home') }}" class="px-3 py-2 hover:bg-green-400 hover:text-white rounded">Beranda</a></li>
        <li><a href="{{ route('layanan') }}" class="px-3 py-2 hover:bg-green-400 hover:text-white rounded">Layanan</a>
        </li>
        <li>
            @auth('pelanggan')
                <a href="{{ route('booking.index') }}" class="px-3 py-2 hover:bg-green-400 hover:text-white rounded">Booking
                    Servis</a>
            @else
                <a href="{{ route('pelanggan.login') }}?intended={{ route('booking.create') }}"
                    class="px-3 py-2 hover:bg-green-400 hover:text-white rounded">Booking Servis</a>
            @endauth
        </li>
        <li><a href="{{ route('status.servis') }}" class="px-3 py-2 hover:bg-green-400 hover:text-white rounded">Status
                Servis</a></li>
        <li><a href="{{ route('kontak') }}" class="px-3 py-2 hover:bg-green-400 hover:text-white rounded">Kontak</a>
        </li>

        @auth('pelanggan')
            <li>
                <div class="relative">
                    <button @click="sidebarNotif = true"
                        class="text-black hover:text-green-600 focus:outline-none w-8 h-8 relative">
                        <i class="fas fa-bell text-2xl"></i>
                        @if ($totalNotif > 0)
                            <span id="notif-count"
                                class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full shadow">
                                {{ $totalNotif }}
                            </span>
                        @endif
                    </button>
                </div>
            </li>

            <li class="relative">
                <button @click="dropdown = !dropdown"
                    class="flex items-center gap-2 px-3 py-2 font-semibold text-black hover:text-green-600">
                    Halo, {{ auth('pelanggan')->user()->nama_lengkap }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="dropdown" @click.away="dropdown = false" x-transition
                    class="absolute right-0 mt-2 w-44 bg-white rounded-md shadow-lg z-50">
                    <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil
                        Saya</a>
                    <form action="{{ route('pelanggan.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                    </form>
                </div>
            </li>
        @else
            <li>
                <a href="{{ route('pelanggan.login') }}"
                    class="border-2 border-black rounded px-3 py-2 hover:bg-green-400 hover:text-white">Masuk/Daftar</a>
            </li>
        @endauth
    </ul>

    {{-- 🔔 Sidebar Notifikasi --}}
    <div x-show="sidebarNotif" class="fixed inset-0 z-50 flex justify-end" x-transition>
        <div @click="sidebarNotif = false" class="w-full bg-black bg-opacity-50"></div>

        <div class="w-[90vw] sm:w-[400px] h-full bg-white shadow-xl border-l relative flex flex-col"
            @click.away="sidebarNotif = false">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-green-700">🔔 Notifikasi Pesanan</h2>
                <button @click="sidebarNotif = false" class="text-gray-500 hover:text-red-500">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="flex justify-between items-center px-6 py-2 border-t text-sm">
                <form action="{{ route('notifikasi.markAllRead') }}" method="POST">
                    @csrf
                    <button class="text-green-600 hover:underline">Tandai semua dibaca</button>
                </form>
                <form action="{{ route('notifikasi.clearAll') }}" method="POST">
                    @csrf
                    <button class="text-red-600 hover:underline">Hapus semua</button>
                </form>
            </div>

            <div id="notif-list" class="flex-1 overflow-y-auto divide-y">
                @forelse ($notifBookings as $notif)
                    <div
                        class="px-6 py-4 transition-all duration-200 {{ $notif->is_read ? '' : 'bg-green-100 border-l-4 border-green-500' }} hover:bg-gray-50">
                        <div class="text-sm font-bold text-gray-800 mb-1">
                            <i class="fas fa-receipt mr-2 text-green-500"></i>
                            Kode: {{ $notif->kode_booking }}
                        </div>
                        <div class="text-sm text-gray-700 mb-1">
                            {!! nl2br(e($notif->pesan)) !!}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ Carbon::parse($notif->created_at)->format('d M Y H:i') }}
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">Belum ada notifikasi pesanan.</div>
                @endforelse
            </div>
        </div>
    </div>
</nav>
