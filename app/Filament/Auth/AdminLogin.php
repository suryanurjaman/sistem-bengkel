<?php

namespace App\Filament\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login;
use Illuminate\Contracts\Support\Htmlable;

class AdminLogin extends Login
{
    protected static string $view = 'filament.auth.custom-login';

    public function getHeading(): string|Htmlable
    {
        return 'Login Admin Bengkel Paijo';
    }

    public function getSubheading(): string|Htmlable
    {
        return 'Silakan login untuk mengelola sistem bengkel.';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Sahabat Motor Paijo';
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->label('Email')
                ->required()
                ->email()
                ->autocomplete()
                ->autofocus(),
            TextInput::make('password')
                ->label('Kata Sandi')
                ->password()
                ->revealable()
                ->required(),
            \Filament\Forms\Components\Checkbox::make('remember')
                ->label('Ingat saya'),
        ];
    }
}
