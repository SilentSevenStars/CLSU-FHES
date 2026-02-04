<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section with Enhanced Styling -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#0a6025] bg-clip-text text-transparent mb-2">
                            Notification Manager
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            Send notifications to applicants
                        </p>
                    </div>
                </div>
            </div>

            <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
                <div class="mb-6 bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-lg shadow-sm" role="alert">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <?php if(session()->has('error')): ?>
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-sm" role="alert">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="block sm:inline"><?php echo e(session('error')); ?></span>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Enhanced Filters and Actions Card -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden mb-6 animate-fadeIn" style="animation-delay: 0.1s;">
                <div class="bg-[#0a6025] p-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                            <i class="fa-solid fa-filter text-white text-lg"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Filters & Actions</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Search
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="search"
                                    wire:model.live="search" 
                                    placeholder="Search by name or email..."
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0a6025] focus:border-transparent shadow-sm transition duration-200"
                                >
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                                    <svg class="shrink-0 size-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="statusFilter" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Status
                            </label>
                            <select 
                                id="statusFilter"
                                wire:model.live="statusFilter"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0a6025] focus:border-transparent shadow-sm transition duration-200"
                            >
                                <option value="all">All Applicants</option>
                                <option value="hired">Hired</option>
                                <option value="not_hired">Not Hired</option>
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-end">
                            <button 
                                wire:click="sendMessage"
                                class="w-full bg-[#0a6025] hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl"
                                <?php if(count($selectedApplicants) === 0): ?> disabled <?php endif; ?>
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Send Message (<?php echo e($totalSelected); ?>)
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Table Card -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn" style="animation-delay: 0.2s;">
                <!-- Table Header with Filter -->
                <div class="bg-[#0a6025] p-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                            <i class="fa-solid fa-users text-white text-lg"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Applicants List</h2>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                    <div class="flex flex-col">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div class="bg-gray-50 border border-gray-300 rounded-xl shadow-2xs overflow-hidden">
                                    <!-- Table -->
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <input 
                                                            type="checkbox" 
                                                            wire:model.live="selectAll"
                                                            class="rounded border-gray-300 text-[#0a6025] focus:ring-[#0a6025]"
                                                        >
                                                    </div>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-black">Name</span>
                                                    </div>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-black">Email</span>
                                                    </div>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-black">Phone</span>
                                                    </div>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-black">Status</span>
                                                    </div>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-black">Notifications</span>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-300 bg-gray-50">
                                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $applicants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $applicant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr class="bg-gray-50 hover:bg-gray-100">
                                                <td class="size-px whitespace-nowrap align-top">
                                                    <div class="px-6 py-3">
                                                        <input 
                                                            type="checkbox" 
                                                            wire:model.live="selectedApplicants"
                                                            value="<?php echo e($applicant->id); ?>"
                                                            class="rounded border-gray-300 text-[#0a6025] focus:ring-[#0a6025]"
                                                        >
                                                    </div>
                                                </td>
                                                <td class="size-px whitespace-nowrap align-top">
                                                    <div class="text-sm font-semibold text-black px-6 py-3">
                                                        <?php echo e($applicant->full_name); ?>

                                                    </div>
                                                </td>
                                                <td class="size-px whitespace-nowrap align-top">
                                                    <div class="text-sm font-medium text-black px-6 py-3">
                                                        <?php echo e($applicant->user->email); ?>

                                                    </div>
                                                </td>
                                                <td class="size-px whitespace-nowrap align-top">
                                                    <div class="text-sm font-medium text-black px-6 py-3">
                                                        <?php echo e($applicant->phone_number ?? 'N/A'); ?>

                                                    </div>
                                                </td>
                                                <td class="size-px whitespace-nowrap align-top">
                                                    <div class="px-6 py-3">
                                                        <!--[if BLOCK]><![endif]--><?php if($applicant->hired): ?>
                                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                                                Hired
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                                Not Hired
                                                            </span>
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    </div>
                                                </td>
                                                <td class="size-px whitespace-nowrap align-top">
                                                    <div class="flex items-center px-6 py-3">
                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                                            </svg>
                                                            <span class="text-sm font-semibold text-black"><?php echo e($applicant->notifications->count()); ?></span>
                                                        </div>
                                                        <!--[if BLOCK]><![endif]--><?php if($applicant->unread_notifications_count > 0): ?>
                                                            <span class="ml-2 px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                                                <?php echo e($applicant->unread_notifications_count); ?> unread
                                                            </span>
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="6" class="px-6 py-12 text-center">
                                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <p class="text-black text-lg font-medium">No applicants found</p>
                                                    <p class="text-gray-600 text-sm mt-1">Try adjusting your search or filters</p>
                                                </td>
                                            </tr>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </tbody>
                                    </table>
                                    <!-- End Table -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\Owner\Desktop\projects\CLSU CAPS\resources\views/livewire/admin/notification-manager.blade.php ENDPATH**/ ?>