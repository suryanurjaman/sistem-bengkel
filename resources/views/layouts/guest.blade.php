<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Bengkel Sahabat Motor Paijo')</title>
    
    @stack('styles')

    @vite('resources/css/app.css')
    
</head>

<body>
    @yield('content')

    {{-- Scripts --}}
    @stack('scripts')
</body>

</html>
