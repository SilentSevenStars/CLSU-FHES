<aside id="panel-sidebar" 
    class="group fixed top-0 left-0 z-50 h-screen transition-all duration-300 ease-in-out
           w-64 sm:w-16 sm:hover:w-64
           -translate-x-full sm:translate-x-0
           bg-white border-r border-gray-200
           overflow-hidden
           sm:hover:shadow-2xl">
   
   <div class="h-full flex flex-col justify-between bg-[#0B712C] px-2 py-4 overflow-y-auto">
      
      <!-- Close Button (Mobile) -->
      <button id="panel-sidebar-close" 
          class="absolute top-4 right-4 sm:hidden text-white hover:bg-[#0A6025] rounded-lg p-2 z-50"
          onclick="togglePanelSidebar()">
         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
         </svg>
      </button>

      <!-- Top Menu -->
      <div class="flex-1">
         <!-- Logo -->
         <div class="flex justify-center mb-6 mt-2">
            <a href="<?php echo e(route('panel.dashboard')); ?>" class="flex items-center justify-center">
               <img src="<?php echo e(asset('image/clsu-logo-green.png')); ?>" 
                    alt="CLSU Logo" 
                    class="h-12 w-auto sm:group-hover:h-28 transition-all duration-300">
            </a>
         </div>

         <!-- Navigation Menu -->
         <ul class="space-y-2 font-medium">
            <li>
               <a href="<?php echo e(route('panel.dashboard')); ?>"
                  class="flex items-center p-3 rounded-lg
                  <?php echo e(request()->routeIs('panel.dashboard') ? 'bg-[#0A6025] text-white' : 'text-white hover:bg-[#0A6025]'); ?>">
                  <i class="fa-solid fa-house text-xl w-5 flex-shrink-0"></i>
                  <span class="ml-4 whitespace-nowrap 
                      block sm:hidden sm:group-hover:block
                      transition-all duration-300">
                     Dashboard
                  </span>
               </a>
            </li>
         </ul>
      </div>

      <!-- User Profile Section (Desktop) -->
      <div class="mb-2 hidden sm:block relative">
        <?php
            $nameParts = explode(' ', Auth::user()->name);
            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
        ?>
        
        <button id="panelSidebarUserButton" 
            class="flex items-center w-full p-2 text-white hover:bg-[#0A6025] rounded-lg relative">
            <div class="w-9 h-9 flex justify-center items-center bg-white text-[#0B712C] font-bold rounded-md flex-shrink-0">
                <?php echo e($initials); ?>

            </div>
            <div class="flex flex-col text-left text-white flex-grow ml-3 overflow-hidden">
               <span class="text-sm font-semibold whitespace-nowrap
                   block sm:hidden sm:group-hover:block
                   transition-all duration-300">
                  <?php echo e(Auth::user()->name); ?>

               </span>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="w-4 h-4 text-gray-200 flex-shrink-0
                 block sm:hidden sm:group-hover:block
                 transition-all duration-300" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div id="panelSidebarUserMenu" 
             class="hidden absolute bottom-full left-0 mb-2 z-50 bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-56">
            <div class="px-4 py-3">
                <p class="text-sm font-medium text-gray-900"><?php echo e(Auth::user()->name); ?></p>
                <p class="text-xs text-gray-500"><?php echo e(Auth::user()->email); ?></p>
            </div>
            <ul class="py-2 text-sm text-gray-700">
                <li><a href="<?php echo e(route('panel.profile-view')); ?>" class="block px-4 py-2 hover:bg-gray-100">Settings</a></li>
            </ul>
            <div class="py-1">
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                    Logout
                    </button>
                </form>
            </div>
        </div>
      </div>
   </div>
</aside><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views/components/nav-panel.blade.php ENDPATH**/ ?>