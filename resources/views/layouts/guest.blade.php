<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <!-- Styles -->
    @livewireStyles
</head>

<body>
    <div class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex">
            <!-- Left Side - Card (match login/register look) -->
            {{ $slot }}

            <!-- Right Side - CLSU Image with Curved Shape (same as login/register) -->
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
                <div class="w-full h-full rounded-[150px_0_0_100px] overflow-hidden">
                    <img src="{{ asset('image/clsu.jpg') }}" alt="CLSU Campus" class="w-full h-full object-cover"
                        onerror="this.src='{{ asset('image/clsu-logo-green.png') }}'; this.onerror=null; this.style.objectFit='contain'; this.style.padding='2rem';">
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>