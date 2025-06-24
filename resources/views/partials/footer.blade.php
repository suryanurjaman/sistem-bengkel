<footer class="bg-[#00A86B] text-black py-6">
    <div class="max-w-7xl mx-auto px-4 md:px-8 flex flex-col md:flex-row gap-8 justify-between">
        {{-- Logo dan Deskripsi --}}
        <div class="flex-1 text-center md:text-left">
            <img src="{{ asset('img/Logo Bengkel.png') }}" alt="Logo" class="w-20 mx-auto md:mx-0 mb-2">
            <p class="text-sm">
                Bengkel Sahabat Motor Paijo: Bengkel terpercaya yang berkomitmen memberikan layanan perawatan dan
                perbaikan kendaraan terbaik serta berkontribusi bagi masyarakat.
            </p>
        </div>

        {{-- Info Kontak --}}
        <div class="flex-1">
            <h5 class="font-semibold text-lg mb-2">Info Kontak</h5>
            <ul class="space-y-1 text-sm">
                <li class="flex items-start gap-2">
                    <i class="fas fa-map-marker-alt mt-1"></i>
                    <span>Jl. Cinangka No.199, Pasirwangi, Bandung</span>
                </li>
                <li class="flex items-center gap-2">
                    <i class="fas fa-phone-alt"></i>
                    <span>+62 856‑5988‑1426</span>
                </li>
            </ul>
        </div>

        {{-- Navigasi Cepat --}}
        <div class="flex-1">
            <h5 class="font-semibold text-lg mb-2">Navigasi Cepat</h5>
            <ul class="space-y-1 text-sm">
                <li><a href="{{ route('home') }}" class="hover:underline">Beranda</a></li>
                <li><a href="{{ route('layanan') }}" class="hover:underline">Layanan</a></li>
                <li>
                    @auth('pelanggan')
                        <a href="{{ route('booking.create') }}" class="hover:underline">Booking Servis</a>
                    @else
                        <a href="{{ route('pelanggan.login') }}" class="hover:underline">Booking Servis</a>
                    @endauth
                </li>
                <li><a href="{{ route('status.servis') }}" class="hover:underline">Status Servis</a></li>
                <li><a href="{{ route('kontak') }}" class="hover:underline">Kontak</a></li>
                @guest('pelanggan')
                    <li><a href="{{ route('pelanggan.login') }}" class="hover:underline">Masuk/Daftar</a></li>
                @else
                    <li><a href="{{ route('home') }}" class="hover:underline">Profil Saya</a></li>
                @endguest
            </ul>
        </div>
    </div>

    <hr class="my-4 border-black">

    <div class="text-center text-xs text-black">
        © 2025 — Bengkel Sahabat Motor Paijo. All rights reserved.
    </div>
</footer>
