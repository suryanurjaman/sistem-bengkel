<?php

namespace App\Http\Controllers\PelangganAuth\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan form login pelanggan
     */
    public function create()
    {
        return view('pelanggan.auth.login');
    }

    /**
     * Proses login pelanggan
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Attempt login dengan guard 'pelanggan'
        if (Auth::guard('pelanggan')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()
            ->withErrors(['email' => __('auth.failed')])
            ->onlyInput('email');
    }

    /**
     * Logout pelanggan
     */
    public function destroy(Request $request)
    {
        Auth::guard('pelanggan')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('pelanggan.login');
    }
}
