<?php

// File: app/Http/Controllers/PelangganAuth/Auth/PasswordResetLinkController.php

namespace App\Http\Controllers\PelangganAuth\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('pelanggan.auth.forgot-password'); // gunakan view pelanggan
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        // Override link reset password default
        \Illuminate\Auth\Notifications\ResetPassword::createUrlUsing(function ($notifiable, $token) use ($request) {
            return url(route('pelanggan.password.reset', [
                'token' => $token,
                'email' => $request->email,
            ], false));
        });

        $status = Password::broker('pelanggans')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
