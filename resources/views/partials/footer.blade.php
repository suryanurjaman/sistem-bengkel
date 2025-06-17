<footer>
    <div class="container">
        <div class="logo">
            <img src="img/Logo Bengkel.png" alt="Logo" />
            <p>Bengkel Sahabat Motor Paijo: Bengkel terpercaya yang berkomitmen memberikan layanan perawatan dan
                perbaikan kendaraan terbaik serta berkontribusi bagi masyarakat.</p>
        </div>
        <div class="info-kontak">
            <h5>Info Kontak</h5>
            <ul>
                <li><i class="fas fa-map-marker-alt"></i> Jl. Cinangka No.199, Pasirwangi, Bandung</li>
                <li><i class="fas fa-phone-alt"></i> +62 856‑5988‑1426</li>
            </ul>
        </div>
        <div class="navigasi-cepat">
            <h5>Navigasi Cepat</h5>
            <ul>
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><a href="{{ route('layanan') }}">Layanan</a></li>
                <li>
                    @auth('pelanggan')
                        <a href="{{ route('booking.create') }}">Booking Servis</a>
                    @else
                        <a href="{{ route('pelanggan.login') }}">Booking Servis</a>
                    @endauth
                </li>
                <li><a href="{{ route('status.servis') }}">Status Servis</a></li>
                <li><a href="{{ route('kontak') }}">Kontak</a></li>
                @guest('pelanggan')
                    <li><a href="{{ route('pelanggan.login') }}">Masuk/Daftar</a></li>
                @else
                    <li><a href="{{ route('home') }}">Profil Saya</a></li>
                @endguest
            </ul>
        </div>
    </div>
    <hr>
    <div class="copyright">© 2025 — Bengkel Sahabat Motor Paijo. All rights reserved.</div>
</footer>
