<?php

namespace App\Http\Controllers\PelangganAuth\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;

class NewPasswordController extends Controller
{
    public function create(Request $request)
    {
        return view('pelanggan.auth.reset-password', [
            'request' => $request,
            'token' => $request->route('token'),
            'email' => $request->email,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::broker('pelanggans')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($pelanggan, $password) {
                $pelanggan->password = Hash::make($password);
                $pelanggan->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('pelanggan.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
