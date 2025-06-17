@php
    $notifBookings = auth('pelanggan')->check()
        ? \App\Models\PemesananServis::where('pelanggan_id', auth('pelanggan')->id())
            ->latest()
            ->with('statusServis')
            ->get()
        : collect();
@endphp

<nav x-data="{ sidebarNotif: false, dropdown: false }">
    <a href="{{ route('home') }}">
        <img src="{{ asset('img/Logo Bengkel.png') }}" alt="Logo Bengkel" class="logo">
    </a>

    <ul class="menu items-center">
        <li><a href="{{ route('home') }}">Beranda</a></li>
        <li><a href="{{ route('layanan') }}">Layanan</a></li>
        <li>
            @auth('pelanggan')
                <a href="{{ route('booking.create') }}">Booking Servis</a>
            @else
                <a href="{{ route('pelanggan.login') }}?intended={{ route('booking.create') }}">
                    Booking Servis
                </a>
            @endauth
        </li>
        <li><a href="{{ route('status.servis') }}">Status Servis</a></li>
        <li><a href="{{ route('kontak') }}">Kontak</a></li>

        @auth('pelanggan')
            {{-- 🔔 Tombol Notifikasi --}}
            <li>
                <button @click="sidebarNotif = true"
                    class="relative text-black hover:text-green-700 focus:outline-none w-8 h-8">
                    <i class="fas fa-bell text-2xl"></i>
                </button>
            </li>



            {{-- 👤 Profil --}}
            <li class="relative">
                <button @click="dropdown = !dropdown"
                    class="flex items-center gap-2 px-3 py-2 font-semibold text-black focus:outline-none">
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
            <li class="Masuk-Daftar">
                <a href="{{ route('pelanggan.login') }}">Masuk/Daftar</a>
            </li>
        @endauth
    </ul>

    {{-- Icon Hamburger Mobile --}}
    <div class="hamburger" onclick="toggleMenu()">
        <span>☰</span>
    </div>

    {{-- 🔔 SIDEBAR NOTIFIKASI --}}
    <div x-show="sidebarNotif" class="fixed inset-0 z-50 flex justify-end" x-transition>
        {{-- Overlay --}}
        <div @click="sidebarNotif = false" class="w-full bg-black bg-opacity-50"></div>

        {{-- Panel Sidebar --}}
        <div class="w-[90vw] sm:w-[400px] h-full bg-white shadow-xl border-l relative flex flex-col"
            @click.away="sidebarNotif = false">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-green-700">🔔 Notifikasi Pesanan</h2>
                <button @click="sidebarNotif = false" class="text-gray-500 hover:text-red-500">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- List Notifikasi --}}
            <div class="flex-1 overflow-y-auto divide-y">
                @forelse ($notifBookings as $notif)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="text-sm font-bold text-gray-800 mb-1">
                            <i class="fas fa-receipt mr-2 text-green-500"></i>
                            Kode: {{ $notif->kode_booking }}
                        </div>
                        <div class="text-sm text-gray-600">
                            Status:
                            <span class="text-black font-medium">
                                {{ $notif->statusServis->nama_status ?? '-' }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">
                            Tanggal:
                            {{ \Carbon\Carbon::parse($notif->tanggal_dipesan)->format('d M Y') }}
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">Belum ada notifikasi pesanan.</div>
                @endforelse
            </div>
        </div>
    </div>
</nav>
