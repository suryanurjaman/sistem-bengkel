
@extends('layouts.guest')

@section('title', 'Masuk – Bengkel Sahabat Motor Paijo')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/Login.css') }}">
@endpush

@section('content')
<div class="logo-top-left">
    <img src="{{ asset('img/Logo Bengkel.png') }}" alt="Logo">
</div>
<div class="container">
    <h3>Selamat Datang</h3>
    <form action="{{ route('pelanggan.login.store') }}" method="POST">
        @csrf
        <div class="input-group">
            <label for="email">Email</label>
            <div class="input-icon">
                <i class="fas fa-envelope"></i>
                <input id="email" name="email" type="email" placeholder="Masukkan email" value="{{ old('email') }}" required>
            </div>
            @error('email')
                <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="password">Kata Sandi</label>
            <div class="input-icon">
                <i class="fas fa-lock"></i>
                <input id="password" name="password" type="password" placeholder="Masukkan kata sandi" required>
            </div>
            @error('password')
                <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div class="forgot-password">
            <a href="{{ route('pelanggan.password.request') }}">Lupa Kata Sandi?</a>
        </div>
        <button type="submit" class="button">Masuk</button>
    </form>
    <div class="register">
        Belum punya akun?
        <a href="{{ route('pelanggan.register') }}">Mendaftar</a>
    </div>
</div>
@endsection
