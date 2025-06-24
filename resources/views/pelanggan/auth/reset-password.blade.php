@extends('layouts.guest')

@section('title', 'Atur Ulang Sandi – Bengkel Sahabat Motor Paijo')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-center text-gray-700 mb-6">Atur Ulang Kata Sandi</h2>

            <form method="POST" action="{{ route('pelanggan.password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <input type="hidden" name="email" value="{{ $request->email }}">

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi Baru</label>
                    <input id="password" type="password" name="password" required autofocus
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-green-600 focus:border-green-600">
                    @error('password')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Kata
                        Sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-green-600 focus:border-green-600">
                </div>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md transition duration-300">
                    Simpan Kata Sandi
                </button>
            </form>
        </div>
    </div>
@endsection
