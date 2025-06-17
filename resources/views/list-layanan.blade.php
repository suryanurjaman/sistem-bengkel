@extends('layouts.app')

@section('title', $kategori->nama_kategori)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ListLayanan.css') }}">
@endpush

@section('content')
    <div class="judul-container">
        <h1>{{ $kategori->nama_kategori }}</h1>
        <p>Berikut beberapa servis {{ $kategori->nama_kategori }} yang tersedia di Bengkel Sahabat Motor Paijo:</p>
    </div>

    <!-- Konten Layanan -->
    <main class="container">
        <section class="service-list">
            @forelse ($layanans as $layanan)
                <div class="service-item">
                    <img src="{{ asset('storage/' . $layanan->gambar) }}" alt="{{ $layanan->nama_layanan }}">

                    <div class="service-info">
                        <h2>{{ $layanan->nama_layanan }}</h2>
                        <p><strong>Harga:</strong> RP.{{ $layanan->harga_min }} - RP.{{ $layanan->harga_max }}</p>
                        <p><strong>Estimasi Tunggu:</strong> 60 - 70 menit</p>
                        <p><strong>Manfaat: </strong>{{ $layanan->deskripsi }}</p>
                    </div>
                </div>
            @empty
                <p>Tidak ada layanan tambahan.</p>
            @endforelse
        </section>
    </main>

    <!-- Ajakan Servis -->
    <div class="servis">
        <h4>Jaga performa motor Anda dengan servis berkualitas di Sahabat Motor Paijo</h4>
        <p>Bengkel terbaik untuk perawatan motor Anda!</p>
        @auth('pelanggan')
            <a href="{{ route('booking.create') }}" class="button">Booking Sekarang</a>
        @else
            <a href="{{ route('pelanggan.login') }}" class="button">Booking Sekarang</a>
        @endauth
    </div><br><br>
@endsection
