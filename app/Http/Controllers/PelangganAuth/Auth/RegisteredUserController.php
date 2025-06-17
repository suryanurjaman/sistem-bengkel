<?php

namespace App\Http\Controllers\PelangganAuth\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan form register pelanggan
     */
    public function create()
    {
        return view('pelanggan.auth.register');
    }

    /**
     * Proses simpan data pelanggan baru, lalu login otomatis
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:pelanggans,email'],
            'password'     => ['required', 'confirmed', Password::defaults()],
            'no_hp'        => ['nullable', 'string', 'max:20'],
            'alamat'       => ['nullable', 'string'],
        ]);

        $pelanggan = Pelanggan::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'no_hp'        => $request->no_hp,
            'alamat'       => $request->alamat,
        ]);

        // Redirect ke halaman register (buat nampilin SweetAlert modal)
        return redirect()
            ->route('pelanggan.register')
            ->with('success', 'Akun berhasil dibuat! Silakan login.');
    }
}
