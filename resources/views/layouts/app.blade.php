<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Bengkel Sahabat Motor')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="//unpkg.com/alpinejs" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite('resources/css/app.css')

    @stack('styles')

    @auth('pelanggan')
        <meta name="pelanggan-id" content="{{ auth('pelanggan')->id() }}">
    @endauth

</head>

<body class="flex flex-col min-h-screen m-0 p-0">


    {{-- Partial Header --}}
    @include('partials.header')

    {{-- Partial Navbar --}}
    @include('partials.navbar')

    {{-- Konten Page --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Partial Footer --}}
    @include('partials.footer')

    @include('components.alert')


    <!-- JS -->
    <script>
        function toggleMenu() {
            const hamburger = document.querySelector('.hamburger');
            const menu = document.querySelector('.menu');
            hamburger.classList.toggle('active');
            menu.classList.toggle('active');
        }
    </script>
    @stack('scripts')

    @vite(['resources/js/app.js'])

</body>

<script src="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.js"></script>

</html>
