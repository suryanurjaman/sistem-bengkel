<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KontakMasuk;
use Illuminate\Support\Facades\Mail;

class KontakController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email',
            'pesan' => 'required|string',
        ]);

        KontakMasuk::create($request->only('nama', 'email', 'pesan'));

        // Kirim email ke admin (opsional)
        Mail::raw("Pesan dari: {$request->nama} ({$request->email})\n\n{$request->pesan}", function ($message) {
            $message->to('suryanurjaman81@gmail.com')->subject('Pesan Baru dari Form Kontak');
        });

        return redirect()->back()->with('status', 'Pesan Anda telah dikirim!');
    }
}
