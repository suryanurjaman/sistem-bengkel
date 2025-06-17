<!-- resources/views/beranda.blade.php -->
@extends('layouts.app')

@section('title', 'Beranda - Bengkel Sahabat Motor Paijo')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/Beranda.css') }}">
@endpush

@php use Illuminate\Support\Str; @endphp

@section('content')

    <!-- Banner -->
    <!-- Banner -->
    <div class="banner" x-data="{
        current: 0,
        images: [
            '{{ asset('img/Banner.jpeg') }}',
            '{{ asset('img/Banner2.jpg') }}',
            '{{ asset('img/Banner3.jpg') }}',
            '{{ asset('img/Banner4.jpg') }}',
        ],
        next() {
            this.current = (this.current + 1) % this.images.length;
        },
        start() {
            setInterval(() => this.next(), 5000);
        }
    }" x-init="start()">
        <img :src="images[current]" alt="Banner">

        <div class="overlay">
            <h1>SELAMAT DATANG DI BENGKEL SAHABAT MOTOR PAIJO</h1>
            <h2>“Perawatan Terbaik untuk Kendaraan Anda!”</h2>
            <p>Memberikan layanan perawatan dan perbaikan kendaraan terbaik bagi pelanggan dengan profesionalisme dan
                kepercayaan.</p>
            @auth('pelanggan')
                <a href="{{ route('booking.create') }}" class="button">Booking Sekarang</a>
            @else
                <a href="{{ route('pelanggan.login') }}" class="button">Booking Sekarang</a>
            @endauth
        </div>
    </div>


    <!-- Judul dan Deskripsi -->
    <div class="text-center">
        <h2 class="title">Kenali Bengkel Sahabat Motor Paijo</h2>
        <p>Bengkel Terpercaya untuk Kendaraan Anda</p>
    </div>

    <div class="container">
        <div class="logo-container">
            <img src="{{ asset('img/Banner Sahabat Motor.jpeg') }}" alt="Logo Bengkel Sahabat Motor" class="logo-bengkel">
        </div>
        <div class="description gap-4">
            <p>Sahabat Motor adalah bengkel terpercaya yang menyediakan layanan perawatan dan perbaikan kendaraan dengan
                mekanik berpengalaman dan teknologi terkini.</p>
            <p>Kami menawarkan berbagai layanan, mulai dari servis rutin, ganti oli, tune-up, hingga perbaikan besar untuk
                menjaga kendaraan Anda tetap optimal.</p>
            <p>Selain mengutamakan kualitas dan kepuasan pelanggan, kami juga berkontribusi dalam menciptakan lapangan kerja
                dan mendukung pertumbuhan ekonomi di sektor otomotif.</p>
        </div>
    </div>

    <!-- Layanan Kami -->
    <div class="text-center">
        <h2 class="title">Layanan Kami</h2>
        <p>Kami menyediakan berbagai layanan perawatan dan perbaikan motor agar kendaraan Anda tetap dalam kondisi prima dan
            nyaman digunakan.</p>
    </div>

    <div class="grid-container">
        @foreach ($kategoriLayanan->take(3) as $kategori)
            <div class="grid-item">
                <h4><i class="fas fa-tools"></i> {{ $kategori->nama_kategori }}</h4>
                <p>{{ $kategori->deskripsi }}</p>

                <ul>
                    @foreach ($kategori->kelebihan as $point)
                        <li>✅ {{ $point['value'] }}</li>
                    @endforeach
                </ul>

                <p><strong>Estimasi:</strong> {{ $kategori->estimasi_waktu }}</p>
                <p><strong>Harga:</strong> Rp{{ number_format($kategori->harga_min, 0, ',', '.') }} -
                    Rp{{ number_format($kategori->harga_max, 0, ',', '.') }}</p>

                <a href="{{ route('layanan.byKategori', Str::slug($kategori->nama_kategori)) }}"
                    class="button">Selengkapnya</a>
            </div>
        @endforeach
    </div>


    <!-- Keunggulan -->
    <div class="text-center">
        <h2 class="title">Kenapa harus memilih Bengkel Sahabat Motor Paijo?</h2>
        <p>Kami berkomitmen memberikan pelayanan terbaik untuk kendaraan Anda dengan standar profesional. Keamanan dan
            kenyamanan berkendara adalah prioritas utama kami!</p>
    </div>
    <ul class="keunggulan-list">
        <li class="keunggulan-item"><i class="fas fa-user-cog"></i> Teknisi berpengalaman & profesional</li>
        <li class="keunggulan-item"><i class="fas fa-tools"></i> Menggunakan suku cadang asli & berkualitas</li>
        <li class="keunggulan-item"><i class="fas fa-wallet"></i> Harga transparan tanpa biaya tersembunyi</li>
        <li class="keunggulan-item"><i class="fas fa-clock"></i> Pelayanan cepat dan ramah</li>
    </ul>

    <!-- Testimoni -->
    <div class="text-center">
        <h2>Testimoni Pelanggan</h2>
        <p>Berikut adalah testimoni dari pelanggan yang telah melakukan servis di Bengkel Sahabat Motor Paijo:</p>
    </div>
    <div class="grid-container-testimoni">
        <div class="grid-item-testimoni">
            <p>"Pelayanan cepat dan cermat, konsultasi kendaraan terbaik. Harga terjangkau. Recommended!"</p>
            <h4>- Benny Kristinah ⭐⭐⭐⭐</h4>
        </div>
        <div class="grid-item-testimoni">
            <p>"Pengerjaan cepat, amanah, sukses selalu."</p>
            <h4>- Windi Anggini ⭐⭐⭐⭐⭐</h4>
        </div>
        <div class="grid-item-testimoni">
            <p>"Pelayanan sangat ramah. Servis memuaskan. Langganan sekeluarga."</p>
            <h4>- Rizky Rimawati ⭐⭐⭐⭐⭐</h4>
        </div>
        <div class="grid-item-testimoni">
            <p>"Servis bagus, memuaskan. Tukang servis ramah. Harga terjangkau."</p>
            <h4>- Bahar Udin ⭐⭐⭐⭐⭐</h4>
        </div>
    </div>

    <!-- Dokumentasi -->
    <div class="text-center">
        <h2 class="title">Dokumentasi Pengerjaan Servis</h2>
        <p>Berikut dokumentasi proses pengerjaan servis di Bengkel Sahabat Motor Paijo.</p>
    </div>
    <div class="grid-container-dokumentasi">
        <div class="grid-item-dokumentasi">
            <img src="{{ asset('img/job1.jpg') }}" alt="Dokumentasi 1" class="img-dokumentasi">
            <p>Proses pengecekan mesin</p>
        </div>
        <div class="grid-item-dokumentasi">
            <img src="{{ asset('img/job2.jpg') }}" alt="Dokumentasi 2" class="img-dokumentasi">
            <p>Penggantian oli</p>
        </div>
        <div class="grid-item-dokumentasi">
            <img src="{{ asset('img/job3.jpg') }}" alt="Dokumentasi 3" class="img-dokumentasi">
            <p>Pemeriksaan rem</p>
        </div>
    </div>

    <!-- Ayo Servis -->
    <div class="servis-section text-center">
        <h2 class="title">Ayo, rawat motor Anda dengan servis terbaik!</h2>
        <p>Pastikan performa motor selalu optimal dengan perawatan berkala di Sahabat Motor Paijo.<br>🔧 Servis sekarang dan
            rasakan kenyamanan berkendara!</p>
        @auth('pelanggan')
            <a href="{{ route('booking.create') }}" class="button">Booking Sekarang</a>
        @else
            <a href="{{ route('pelanggan.login') }}" class="button">Booking Sekarang</a>
        @endauth
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const hamburger = document.querySelector(".hamburger");
            const menu = document.querySelector(".menu");
            const icon = hamburger.querySelector("span");
            hamburger.addEventListener("click", () => {
                hamburger.classList.toggle("active");
                menu.classList.toggle("active");
                icon.textContent = hamburger.classList.contains("active") ? "✕" : "☰";
            });
        });
    </script>
@endpush
