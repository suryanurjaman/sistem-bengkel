@extends('layouts.app')

@section('title', 'Kontak - Bengkel Sahabat Motor Paijo')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/Kontak.css') }}">
@endpush

@section('content')
    <div class="kontak-section">
        <h1 class="layanan-title"><b>Kontak</b></h1>
        <p><i>Butuh informasi lebih lanjut atau ingin konsultasi tentang servis motor Anda? <br>
                📩 Hubungi kami melalui formulir di bawah ini!!!
            </i></p>
        <div class="garis-hr"></div>
    </div>

    <div class="card-container mt-10 grid gap-6 grid-cols-1 md:grid-cols-3 px-6">
        <div class="card bg-white p-6 rounded shadow text-center">
            <i class="fas fa-map-marker-alt text-green-600 text-3xl mb-2"></i>
            <h3 class="text-xl font-semibold">Alamat</h3>
            <p>Jl. Cinangka No.199, Pasirwangi, Kec. Ujung Berung, Kota Bandung, Jawa Barat 40618</p>
        </div>
        <div class="card bg-white p-6 rounded shadow text-center">
            <i class="fas fa-phone text-green-600 text-3xl mb-2"></i>
            <h3 class="text-xl font-semibold">Telepon</h3>
            <p>+62 856-5988-1426</p>
        </div>
        <div class="card bg-white p-6 rounded shadow text-center">
            <i class="fas fa-clock text-green-600 text-3xl mb-2"></i>
            <h3 class="text-xl font-semibold">Jam Operasional</h3>
            <p>Senin – Kamis: 07.30 – 17.00</p>
            <p>Sabtu – Minggu: 07.30 – 17.00</p>
        </div>
    </div>

    <div class="container mx-auto px-6 mt-12 grid grid-cols-1 md:grid-cols-2 gap-10">
        <div class="contact-form-container">
            {{-- Tampilkan notifikasi sukses --}}
            @if (session('status'))
                <div class="mb-4 text-green-600 font-semibold">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Form kirim ke backend --}}
            <form method="POST" action="{{ route('kontak.kirim') }}" class="contact-form space-y-4">
                @csrf
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama:</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                        class="w-full mt-1 rounded border-gray-300 @error('nama') border-red-500 @enderror" required>
                    @error('nama')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="w-full mt-1 rounded border-gray-300 @error('email') border-red-500 @enderror" required>
                    @error('email')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="pesan" class="block text-sm font-medium text-gray-700">Pesan:</label>
                    <textarea id="pesan" name="pesan" rows="5"
                        class="w-full mt-1 rounded border-gray-300 @error('pesan') border-red-500 @enderror" required>{{ old('pesan') }}</textarea>
                    @error('pesan')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">
                    Kirim
                </button>
            </form>
        </div>

        <div class="map-container">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d84664.40018667934!2d107.56059714335939!3d-6.901651700000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68dd182bf2aa21%3A0x5ef475a7d3523165!2sSAHABAT%20MOTOR%20Paijo!5e1!3m2!1sid!2sid!4v1742266564137!5m2!1sid!2sid"
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Tidak perlu pakai handleKontak JS lagi --}}
@endpush
