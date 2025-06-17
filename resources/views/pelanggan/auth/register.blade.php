@extends('layouts.guest')

@section('title', 'Daftar Akun – Bengkel Sahabat Motor Paijo')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/Register.css') }}">
@endpush

@section('content')
    <div class="logo-top-left">
        <img src="{{ asset('img/Logo Bengkel.png') }}" alt="Logo">
    </div>

    <div class="container">
        <h3>Mendaftar</h3>
        <p>Isi form di bawah ini untuk membuat akun baru</p>

        <form action="{{ route('pelanggan.register.store') }}" method="POST">
            @csrf

            <div class="input-group">
                <label for="email">Email</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input id="email" name="email" type="email" placeholder="Masukkan email"
                        value="{{ old('email') }}" required>
                </div>
                @error('email')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <label for="name">Nama</label>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input id="nama_lengkap" name="nama_lengkap" type="text" placeholder="Masukkan nama Anda"
                        value="{{ old('nama_lengkap') }}" required>
                </div>
            </div>

            <div class="input-group">
                <label for="phone">Nomor Telepon</label>
                <div class="input-icon">
                    <i class="fas fa-phone"></i>
                    <input id="no_hp" name="no_hp" type="tel" placeholder="Masukkan nomor telepon Anda"
                        value="{{ old('no_hp') }}">
                </div>
            </div>

            <div class="input-group">
                <label for="password">Kata Sandi</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" required>
                </div>
            </div>

            <div class="input-group">
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password_confirmation" required>
                </div>
                @error('password')
                    <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="button">Mendaftar</button>
        </form>

        <div class="register">
            Sudah punya akun?
            <a href="{{ route('pelanggan.login') }}">Login</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Ke Login',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('pelanggan.login') }}";
                }
            });
        @endif
    </script>
@endpush
