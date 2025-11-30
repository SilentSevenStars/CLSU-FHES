<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-white border-r">
    <div class="h-full flex flex-col justify-between px-3 py-4 overflow-y-auto bg-[#0B712C]">

        <!-- Top Menu -->
        <div>
            <a href="{{ route('admin.dashboard') }}" class="flex justify-center mb-6">
                <img src="{{ asset('image/clsu-logo-green.png') }}" alt="CLSU Logo" class="h-28 w-auto">
            </a>

            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center p-2 rounded-lg 
                  {{ request()->routeIs('admin.dashboard') ? 'bg-[#0A6025] text-white' : 'text-white hover:bg-[#0A6025]' }}">
                        <i class="fa-solid fa-house mr-1"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.position') }}"
                        class="flex items-center p-2 rounded-lg 
                  {{ request()->routeIs('admin.position') ? 'bg-[#0A6025] text-white' : 'text-white hover:bg-[#0A6025]' }}">
                        <i class="fa-solid fa-briefcase mr-1"></i> Position
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.applicant') }}"
                        class="flex items-center p-2 rounded-lg 
                  {{ request()->routeIs('admin.applicant') ? 'bg-[#0A6025] text-white' : 'text-white hover:bg-[#0A6025]' }}">
                        <i class="fa-solid fa-users text-xl w-5 flex-shrink-0 mr-1"></i> Applicant
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.scheduled') }}"
                        class="flex items-center p-2 rounded-lg 
                  {{ request()->routeIs('admin.scheduled') ? 'bg-[#0A6025] text-white' : 'text-white hover:bg-[#0A6025]' }}">
                        <i class="fa-solid fa-calendar-check text-xl w-5 flex-shrink-0 mr-1"></i> Scheduled Applicant
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.panel') }}"
                        class="flex items-center p-2 rounded-lg 
                  {{ request()->routeIs('admin.panel') ? 'bg-[#0A6025] text-white' : 'text-white hover:bg-[#0A6025]' }}">
                        <i class="fa-solid fa-user-tie text-xl w-5 flex-shrink-0 mr-1"></i> Panel
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.screening') }}"
                        class="flex items-center p-2 rounded-lg 
                  {{ request()->routeIs('admin.panel') ? 'bg-[#0A6025] text-white' : 'text-white hover:bg-[#0A6025]' }}">
                        <i class="fa-solid fa-user-tie text-xl w-5 flex-shrink-0 mr-1"></i> Screening
                    </a>
                </li>
            </ul>
        </div>

        <!-- ✅ Hide this user dropdown on mobile -->
        <div class="mb-2 hidden sm:block">
            @php
            $nameParts = explode(' ', Auth::user()->name);
            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
            @endphp

            <button id="sidebarUserButton" data-dropdown-toggle="sidebarUserMenu"
                class="flex items-center w-full p-2 text-white hover:bg-[#0A6025] rounded-lg">
                <div class="w-9 h-9 flex justify-center items-center bg-white text-[#0B712C] font-bold rounded-md me-3">
                    {{ $initials }}
                </div>
                <div class="flex flex-col text-left text-white flex-grow">
                    <span class="text-sm font-semibold flex items-center gap-1 ">
                        <span class="truncate max-w-[140px]">
                            {{ Auth::user()->name }}
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-200" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </div>
            </button>

            <div id="sidebarUserMenu" class="hidden z-50 bg-white divide-y divide-gray-100 rounded-lg shadow w-56">
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
    </div>
</aside>