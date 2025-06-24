@extends('layouts.guest')

@section('title', 'Daftar Akun – Bengkel Sahabat Motor Paijo')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush

@section('content')
    {{-- Latar belakang putih dan hijau --}}
    <div class="relative min-h-screen">
        <div class="absolute bottom-0 left-0 w-full h-[40%] bg-[#00A86B] z-0"></div>

        {{-- Logo kiri atas --}}
        <div class="absolute top-5 left-5 z-10">
            <img src="{{ asset('img/Logo Bengkel.png') }}" alt="Logo" class="w-20">
        </div>

        {{-- Form Register --}}
        <div class="relative z-10 flex items-center justify-center min-h-screen">
            <div class="bg-white shadow-2xl rounded-lg px-10 py-10 w-[90%] max-w-md">
                <h3 class="text-xl font-bold text-center mb-2">Mendaftar</h3>
                <p class="text-sm text-center mb-6 text-gray-600">Isi form di bawah ini untuk membuat akun baru</p>

                <form action="{{ route('pelanggan.register.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input id="email" name="email" type="email" placeholder="Masukkan email"
                                value="{{ old('email') }}" required
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-green-600 focus:border-green-600">
                        </div>
                        @error('email')
                            <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input id="nama_lengkap" name="nama_lengkap" type="text" pattern="[A-Za-z\s]+"
                                title="Hanya huruf dan spasi" placeholder="Masukkan nama Anda"
                                value="{{ old('nama_lengkap') }}" required
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-green-600 focus:border-green-600">
                        </div>
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <div class="relative">
                            <i class="fas fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input id="no_hp" name="no_hp" type="tel" maxlength="13" minlength="10"
                                pattern="^08[0-9]{8,11}$" placeholder="Contoh: 081234567890" required
                                title="Nomor telepon harus diawali dengan 08 dan berisi 10-13 digit angka"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-green-600 focus:border-green-600" />
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="password" name="password" id="password" minlength="6" required
                                title="Minimal 6 karakter"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-green-600 focus:border-green-600">
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi
                            Kata Sandi</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="password" name="password_confirmation" id="password_confirmation" minlength="6"
                                required
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-green-600 focus:border-green-600">
                        </div>
                        @error('password')
                            <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tombol Submit --}}
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md transition duration-300">
                        Mendaftar
                    </button>
                </form>

                <div class="text-center text-sm mt-6">
                    Sudah punya akun?
                    <a href="{{ route('pelanggan.login') }}" class="text-green-600 hover:underline">Login</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Swal --}}
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

    {{-- Validasi angka di input no_hp --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const noHpInput = document.getElementById('no_hp');
            noHpInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
@endpush
