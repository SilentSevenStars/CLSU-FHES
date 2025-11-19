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
    @php
        $nameParts = explode(' ', Auth::user()->name);
        $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
    @endphp

    <button data-dropdown-toggle="mobileUserMenu" class="w-9 h-9 flex justify-center items-center bg-[#0B712C] text-white font-bold rounded-md">
        {{ $initials }}
    </button>

    <!-- ✅ Profile Dropdown -->
    <div id="mobileUserMenu"
        class="hidden z-50 bg-white divide-y divide-gray-200 rounded-lg shadow w-56 mt-2">

        <div class="px-4 py-3">
            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
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