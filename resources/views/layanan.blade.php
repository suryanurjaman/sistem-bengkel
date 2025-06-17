@extends('layouts.app')

@section('title', 'Layanan Servis - Bengkel Sahabat Motor Paijo')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/Layanan.css') }}">
@endpush

@section('content')

    <div class="layanan-section">
        <h1 class="layanan-title"><b>Layanan</b></h1>
        <p><i>Temukan berbagai layanan servis yang kami sediakan untuk memastikan kendaraan Anda selalu dalam kondisi
                terbaik. <br>
                Percayakan perawatan motor Anda pada Bengkel Sahabat Motor Paijo, solusi terpercaya untuk kendaraan Anda.
            </i></p>
        <div class="garis-hr"></div>
    </div>

    <div class="promo-spesial">
        <h2 class="promo-title"><i class="fas fa-star"></i> Promo Loyalitas untuk Pelanggan Setia</h2>
        <p class="promo-desc">
            <i class="fas fa-tools"></i> <strong>Bengkel Sahabat Motor Paijo</strong> mengapresiasi loyalitas Anda! Nikmati
            keuntungan eksklusif setelah melakukan
            <span class="promo-highlight">3 kali booking</span> di bengkel kami.
        </p>
        <p class="promo-extra">
            <i class="fas fa-gift"></i> Servis ketiga dapatkan <span class="promo-highlight">diskon 10%</span> untuk semua
            layanan.
        </p>
        <p class="promo-period">
            <i class="fas fa-handshake"></i> Promo ini khusus untuk pelanggan setia Bengkel Sahabat Motor yang rutin
            mempercayakan perawatan kendaraan bersama kami.
        </p>
    </div>

    <div class="container">
        @foreach ($kategoris as $kategori)
            <div class="card">
                <h3><i class="fas fa-tools"></i> {{ $kategori->nama_kategori }}</h3>
                <p>{{ $kategori->deskripsi }}</p>
                <p><i class="fas fa-clock"></i> Estimasi: {{ $kategori->estimasi_waktu }}</p>
                <p class="price">
                    <i class="fas fa-wallet"></i>
                    Rp{{ number_format($kategori->harga_min, 0, ',', '.') }} -
                    Rp{{ number_format($kategori->harga_max, 0, ',', '.') }}
                </p>
                <a href="{{ route('layanan.byKategori', Str::slug($kategori->nama_kategori)) }}"
                    class="button">Selengkapnya</a>
            </div>
        @endforeach
    </div>

@endsection

@push('scripts')
    <script>
        function toggleMenu() {
            const menu = document.querySelector(".menu");
            const hamburger = document.querySelector(".hamburger span");
            menu.classList.toggle("active");
            hamburger.textContent = menu.classList.contains("active") ? "✕" : "☰";
        }
    </script>
@endpush
