<aside id="admin-sidebar" x-data="{
        applicantOpen: false,
        screeningOpen: false,
        nbcOpen: false,
        managementOpen: false,
        closeAll() {
            this.applicantOpen = false;
            this.screeningOpen = false;
            this.nbcOpen = false;
            this.managementOpen = false;
        }
    }" @mouseleave="closeAll()" class="group fixed top-0 left-0 z-50 h-screen transition-all duration-300 ease-in-out
           w-64 sm:w-16 sm:hover:w-64
           -translate-x-full sm:translate-x-0
           bg-white border-r border-gray-200
           overflow-hidden
           sm:hover:shadow-2xl">

   <div class="h-full flex flex-col justify-between bg-[#0B712C] px-2 py-4 overflow-y-auto">

      <!-- Close Button (Mobile) -->
      <button id="admin-sidebar-close"
         class="absolute top-4 right-4 sm:hidden text-white hover:bg-[#0A6025] rounded-lg p-2 z-50"
         onclick="toggleAdminSidebar()">
         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
         </svg>
      </button>

      <!-- Top Menu -->
      <div class="flex-1">
         <!-- Logo -->
         <div class="flex justify-center mb-6 mt-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center">
               <img src="{{ asset('image/clsu-logo-green.png') }}" alt="CLSU Logo"
                  class="h-12 w-auto sm:group-hover:h-28 transition-all duration-300">
            </a>
         </div>

         <!-- Navigation Menu -->
         <ul class="space-y-2 font-medium">
            <li>
               <a href="{{ route('admin.dashboard') }}"
                  class="flex items-center p-3 rounded-lg
                  {{ request()->routeIs('admin.dashboard') ? 'bg-[#0A6025] text-white' : 'text-white hover:bg-[#0A6025]' }}">
                  <i class="fa-solid fa-house text-xl w-5 flex-shrink-0"></i>
                  <span class="ml-4 whitespace-nowrap 
                      block sm:hidden sm:group-hover:block
                      transition-all duration-300">
                     Dashboard
                  </span>
               </a>
            </li>
            <li>
               <a href="{{ route('admin.position') }}"
                  class="flex items-center p-3 rounded-lg
                  {{ request()->routeIs('admin.position') ? 'bg-[#0A6025] text-white' : 'text-white hover:bg-[#0A6025]' }}">
                  <i class="fa-solid fa-briefcase text-xl w-5 flex-shrink-0"></i>
                  <span class="ml-4 whitespace-nowrap 
                      block sm:hidden sm:group-hover:block
                      transition-all duration-300">
                     Position
                  </span>
               </a>
            </li>
            <li>
               <button @click="applicantOpen = !applicantOpen"
                  class="flex items-center w-full p-3 rounded-lg text-white hover:bg-[#0A6025]">
                  <i class="fa-solid fa-users text-xl w-5"></i>
                  <span class="ml-4 flex-1 block sm:hidden sm:group-hover:block">Applicant</span>
                  <i class="fa-solid fa-chevron-down text-xs transition-transform"
                     :class="applicantOpen ? 'rotate-180' : ''"></i>
               </button>

               <ul x-show="applicantOpen" x-transition class="ml-8 mt-1 space-y-1 text-sm">
                  <li>
                     <a href="{{ route('admin.applicant') }}" class="block p-2 rounded hover:bg-[#0A6025] text-white">
                        Applicants
                     </a>
                  </li>
                  <li>
                     <a href="{{ route('admin.scheduled') }}" class="block p-2 rounded hover:bg-[#0A6025] text-white">
                        Scheduled Applicant
                     </a>
                  </li>
                  <li>
                     <a href="{{ route('admin.assign.position') }}"
                        class="block p-2 rounded hover:bg-[#0A6025] text-white">
                        Assign Position
                     </a>
                  </li>
               </ul>
            </li>
            <li>
               <button @click="screeningOpen = !screeningOpen"
                  class="flex items-center w-full p-3 rounded-lg text-white hover:bg-[#0A6025]">
                  <i class="fa-solid fa-clipboard-check text-xl w-5"></i>
                  <span class="ml-4 flex-1 block sm:hidden sm:group-hover:block">Screening</span>
                  <i class="fa-solid fa-chevron-down text-xs" :class="screeningOpen ? 'rotate-180' : ''"></i>
               </button>

               <ul x-show="screeningOpen" x-transition class="ml-8 mt-1 space-y-1 text-sm">
                  <li>
                     <a href="{{ route('admin.screening') }}" class="block p-2 rounded hover:bg-[#0A6025] text-white">
                        Screening
                     </a>
                  </li>
                  <li>
                     <a href="{{ route('admin.panel') }}" class="block p-2 rounded hover:bg-[#0A6025] text-white">
                        Panel
                     </a>
                  </li>
                  <li>
                     <a href="{{ route('admin.representative') }}" class="block p-2 rounded hover:bg-[#0A6025] text-white">
                        Representative
                     </a>
                  </li>
               </ul>
            </li>
            <li>
               <button @click="nbcOpen = !nbcOpen"
                  class="flex items-center w-full p-3 rounded-lg text-white hover:bg-[#0A6025]">
                  <i class="fa-solid fa-scale-balanced text-xl w-5"></i>
                  <span class="ml-4 flex-1 block sm:hidden sm:group-hover:block">NBC</span>
                  <i class="fa-solid fa-chevron-down text-xs" :class="nbcOpen ? 'rotate-180' : ''"></i>
               </button>

               <ul x-show="nbcOpen" x-transition class="ml-8 mt-1 space-y-1 text-sm">
                  <li>
                     <a href="{{ route('admin.nbc') }}" class="block p-2 rounded hover:bg-[#0A6025] text-white">
                        NBC
                     </a>
                  </li>
                  <li>
                     <a href="{{ route('admin.nbc.comittee') }}"
                        class="block p-2 rounded hover:bg-[#0A6025] text-white">
                        NBC Committee
                     </a>
                  </li>
               </ul>
            </li>

            <li>
               <button @click="managementOpen = !managementOpen"
                  class="flex items-center w-full p-3 rounded-lg text-white hover:bg-[#0A6025]">
                  <i class="fa-solid fa-gear text-xl w-5"></i>
                  <span class="ml-4 flex-1 block sm:hidden sm:group-hover:block">Management</span>
                  <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="managementOpen ? 'rotate-180' : ''"></i>
               </button>

               <ul x-show="managementOpen" x-transition class="ml-8 mt-1 space-y-1 text-sm">
                  <li>
                     <a href="{{ route('admin.position.rank') }}" class="block p-2 rounded hover:bg-[#0A6025] text-white">
                        Position
                     </a>
                  </li>
                  <li>
                     <a href="{{ route('admin.college') }}" class="block p-2 rounded hover:bg-[#0A6025] text-white">
                        College
                     </a>
                  </li>
                  <li>
                     <a href="{{ route('admin.department') }}" class="block p-2 rounded hover:bg-[#0A6025] text-white">
                        Department
                     </a>
                  </li>
               </ul>
            </li>

            <li>
               <a href="{{ route('admin.notifications') }}"
                  class="flex items-center p-3 rounded-lg
                  {{ request()->routeIs('admin.notifications') ? 'bg-[#0A6025] text-white' : 'text-white hover:bg-[#0A6025]' }}">
                  <i class="fa-solid fa-user-tie text-xl w-5 flex-shrink-0"></i>
                  <span class="ml-4 whitespace-nowrap 
                      block sm:hidden sm:group-hover:block
                      transition-all duration-300">
                     Message
                  </span>
               </a>
            </li>
         </ul>
      </div>

      <!-- User Profile Section (Desktop) -->
      <div class="mb-2 hidden sm:block relative">
         @php
         $nameParts = explode(' ', Auth::user()->name);
         $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
         @endphp

         <button id="adminSidebarUserButton"
            class="flex items-center w-full p-2 text-white hover:bg-[#0A6025] rounded-lg relative">
            <div
               class="w-9 h-9 flex justify-center items-center bg-white text-[#0B712C] font-bold rounded-md flex-shrink-0">
               {{ $initials }}
            </div>
            <div class="flex flex-col text-left text-white flex-grow ml-3 overflow-hidden">
               <span class="text-sm font-semibold whitespace-nowrap
                   block sm:hidden sm:group-hover:block
                   transition-all duration-300">
                  {{ Auth::user()->name }}
               </span>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-200 flex-shrink-0
                 block sm:hidden sm:group-hover:block
                 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
         </button>

         <div id="adminSidebarUserMenu"
            class="hidden absolute bottom-full left-0 mb-2 z-50 bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-56">
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