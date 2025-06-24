@extends('layouts.guest')

@section('title', 'Lupa Kata Sandi – Bengkel Sahabat Motor Paijo')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush

@section('content')
    <div class="relative min-h-screen bg-gray-50 flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
            <div class="mb-4 text-center">
                <img src="{{ asset('img/Logo Bengkel.png') }}" class="w-16 mx-auto mb-2">
                <h2 class="text-xl font-semibold text-gray-700">Lupa Kata Sandi</h2>
                <p class="text-sm text-gray-500">Masukkan email yang terdaftar untuk mengatur ulang sandi.</p>
            </div>

            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 text-sm rounded p-3 mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('pelanggan.password.email') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="email" name="email" id="email" required autofocus
                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-green-600 focus:border-green-600"
                            placeholder="Masukkan email Anda" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md transition duration-300">
                    Kirim Link Reset
                </button>
            </form>

            <div class="mt-6 text-center text-sm">
                <a href="{{ route('pelanggan.login') }}" class="text-green-600 hover:underline">Kembali ke Login</a>
            </div>
        </div>
    </div>
@endsection
