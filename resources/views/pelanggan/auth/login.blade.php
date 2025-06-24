@extends('layouts.guest')

@section('title', 'Masuk – Bengkel Sahabat Motor Paijo')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush

@section('content')
    {{-- Latar belakang putih dan hijau --}}
    <div class="relative min-h-screen">
        {{-- Latar bawah warna hijau 45% --}}
        <div class="absolute bottom-0 left-0 w-full h-[40%] bg-[#00A86B] z-0"></div>

        {{-- Logo kiri atas --}}
        <div class="absolute top-5 left-5 z-10">
            <img src="{{ asset('img/Logo Bengkel.png') }}" alt="Logo" class="w-20">
        </div>

        {{-- Form login di tengah --}}
        <div class="relative z-10 flex items-center justify-center min-h-screen">
            <div class="bg-white drop-shadow-2xl rounded-lg px-10 py-12 w-[90%] max-w-md">

                <h3 class="text-xl font-bold text-center mb-10">Selamat Datang</h3>

                <form action="{{ route('pelanggan.login.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input id="email" name="email" type="email" placeholder="Masukkan email"
                                value="{{ old('email') }}" required
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-600 focus:border-green-600 text-sm">
                        </div>
                        @error('email')
                            <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input id="password" name="password" type="password" placeholder="Masukkan kata sandi" required
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-600 focus:border-green-600 text-sm">
                        </div>
                        @error('password')
                            <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Lupa Password --}}
                    <div class="text-right text-sm">
                        <a href="{{ route('pelanggan.password.request') }}" class="text-gray-700 hover:underline">Lupa Kata
                            Sandi?</a>
                    </div>

                    {{-- Tombol Login --}}
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md transition duration-300">
                        Masuk
                    </button>
                </form>

                {{-- Register --}}
                <div class="text-center text-sm mt-6">
                    Belum punya akun?
                    <a href="{{ route('pelanggan.register') }}" class="text-green-600 hover:underline">Mendaftar</a>
                </div>
            </div>
        </div>
    </div>
@endsection
