<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{{ config('app.name', 'Laravel') }}</title>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif
@livewireStyles
</head>

<body class="bg-gray-50">
<!-- Mobile menu button -->
@include('components.mobile-view')

<!-- Sidebar -->
@if(Auth::user()->role === 'admin')
    @include('components.nav-admin')
@elseif(Auth::user()->role === 'panel')
    @include('components.nav-panel')
@else
    @include('components.nav-applicant')
@endif

<!-- Main content -->
@if(in_array(Auth::user()->role, ['applicant', 'admin', 'panel']))
<main class="p-4 sm:ml-16 min-h-screen">
    {{ $slot }}
</main>
@else
<main class="p-4 sm:ml-64 min-h-screen">
    {{ $slot }}
</main>
@endif
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/chart.js') }}"></script>
@livewireScripts
</body>
</html>
