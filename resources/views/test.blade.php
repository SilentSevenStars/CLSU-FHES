<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{{ config('app.name', 'Laravel') }}</title>
@if(file_exists(public_path('build/manifest.json')))
    file_exists(public_path('hot'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif
@livewireStyles
</head>

<body class="bg-gray-50">

<!-- Mobile menu button -->
<button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"
    class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
   <span class="sr-only">Open sidebar</span>
   <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
      <path clip-rule="evenodd" fill-rule="evenodd"
         d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
      </path>
   </svg>
</button>

<!-- ✅ ADDED: Mobile profile button TOP RIGHT -->
<div class="absolute top-2 right-2 sm:hidden">
    <button data-dropdown-toggle="mobileUserMenu"
        class="w-9 h-9 flex justify-center items-center bg-[#0B712C] text-white font-bold rounded-md">
        JR
    </button>

    <!-- ✅ Profile Dropdown -->
    <div id="mobileUserMenu"
        class="hidden z-50 bg-white divide-y divide-gray-200 rounded-lg shadow w-56 mt-2">

        <div class="px-4 py-3">
            <p class="text-sm font-medium text-gray-900">Joseph Matthew Ringor</p>
            <p class="text-xs text-gray-500">josephmatthewringor@gmail.com</p>
        </div>

        <ul class="py-2 text-sm text-gray-700">
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Settings</a></li>
        </ul>

        <div class="py-1">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                Logout
                </button>
            </form>
        </div>
    </div>
</div>
<!-- ✅ END ADDED -->

<!-- Sidebar -->
<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-white border-r">
   <div class="h-full flex flex-col justify-between px-3 py-4 overflow-y-auto bg-[#0B712C]">

      <!-- Top Menu -->
      <div>
         <div class="flex justify-center mb-6">
            <img src="{{ asset('image/clsu-logo-green.png') }}" alt="CLSU Logo" class="h-28 w-auto">
        </div>

         <ul class="space-y-2 font-medium">
            <li><a href="#" class="flex items-center p-2 text-white rounded-lg hover:bg-[#0A6025]">Dashboard</a></li>
            <li><a href="#" class="flex items-center p-2 text-white rounded-lg hover:bg-[#0A6025]">Users</a></li>
            <li><a href="#" class="flex items-center p-2 text-white rounded-lg hover:bg-[#0A6025]">Products</a></li>
            <li><a href="#" class="flex items-center p-2 text-white rounded-lg hover:bg-[#0A6025]">Settings</a></li>
         </ul>
      </div>

      <!-- ✅ Hide this user dropdown on mobile -->
      <div class="mb-2 hidden sm:block">
        <button id="sidebarUserButton" data-dropdown-toggle="sidebarUserMenu"
            class="flex items-center w-full p-2 text-white hover:bg-[#0A6025] rounded-lg">
            <div class="w-9 h-9 flex justify-center items-center bg-white text-[#0B712C] font-bold rounded-md me-3">
                JR
            </div>
            <div class="flex flex-col text-left text-white flex-grow">
                <span class="text-sm font-semibold flex items-center gap-1 ">
                    <span class="truncate max-w-[140px]">
                        Joseph Matthew B. Ringor
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </span>
            </div>
        </button>

        <div id="sidebarUserMenu"
            class="hidden z-50 bg-white divide-y divide-gray-100 rounded-lg shadow w-56">
            <div class="px-4 py-3">
                <p class="text-sm font-medium text-gray-900">Joseph Matthew Ringor</p>
                <p class="text-xs text-gray-500">josephmatthewringor@gmail.com</p>
            </div>
            <ul class="py-2 text-sm text-gray-700">
                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Settings</a></li>
            </ul>
            <div class="py-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                    Logout
                    </button>
                </form>
            </div>
        </div>
      </div>
   </div>
</aside>

<!-- Main content -->
<main class="p-4 sm:ml-64">
    {{ $slot }}
</main>

@livewireScripts
</body>
</html>
