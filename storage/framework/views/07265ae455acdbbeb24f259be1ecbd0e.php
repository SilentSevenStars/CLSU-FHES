<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section with Enhanced Styling -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#0a6025] bg-clip-text text-transparent mb-2">
                            Panel
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0B712C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            Manage Panel
                        </p>
                    </div>
                </div>
            </div>

            <!-- Enhanced Table Card -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn" style="animation-delay: 0.3s;">
                <!-- Table Header with Filter -->
                <div class="bg-[#0a6025] to-indigo-600 p-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <!-- Left: Title -->
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <i class="fa-solid fa-briefcase text-white text-lg"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Panel Lists</h2>
                        </div>

                        <!-- Right: Search + Filter + Create -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Position Filter -->
                            <select wire:model.live="filterPosition"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white">
                                <option value="all">All Positions</option>
                                <option value="head">Head</option>
                                <option value="señior">Senior</option>
                                <option value="dean">Dean</option>
                            </select>

                            <!-- College Filter (using college_id) -->
                            <select wire:model.live="filterCollege"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white">
                                <option value="all">All Colleges</option>
                                <?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                                <option value="<?php echo e($col->id); ?>"><?php echo e($col->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                            <!-- Department Filter (using department_id) -->
                            <select wire:model.live="filterDepartment"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white">
                                <option value="all">All Departments</option>
                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                                <option value="<?php echo e($dept->id); ?>"><?php echo e($dept->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                            <!-- Per Page -->
                            <select wire:model="perPage"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white">
                                <option value="5">5 / page</option>
                                <option value="10">10 / page</option>
                                <option value="15">15 / page</option>
                                <option value="20">20 / page</option>
                                <option value="50">50 / page</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <!-- Table Section -->
                <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                    <!-- Card -->
                    <div class="flex flex-col">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div class="bg-white border border-gray-200 rounded-xl shadow-2xs overflow-hidden">
                                    <!-- Header -->
                                    <div
                                        class="px-6 py-4 flex flex-wrap items-center justify-between border-b border-gray-200 gap-3 bg-white">
                                        <!-- Search Input -->
                                        <div class="flex-1 min-w-[200px] max-w-md">
                                            <label for="hs-as-table-product-review-search"
                                                class="sr-only">Search</label>
                                            <div class="relative">
                                                <input type="text" wire:model.live="search"
                                                    class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-[#0B712C] focus:ring-[#0B712C] bg-white text-gray-900 placeholder-gray-500"
                                                    placeholder="Search by name or department...">
                                                <div
                                                    class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                                    <svg class="shrink-0 size-4 text-gray-600"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="11" cy="11" r="8" />
                                                        <path d="m21 21-4.3-4.3" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Create Button -->
                                        <div>
                                            <button wire:click="openCreateModal" class="block text-white bg-[#0D7A2F] hover:bg-[#0a6025] focus:ring-4 focus:ring-blue-300 
                                        font-medium rounded-lg text-sm px-5 py-2.5">
                                                Create Panel
                                            </button>
                                        </div>
                                    </div>
                                    <!-- End Header -->

                                    <!-- Table -->
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-gray-800">
                                                            Name
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-gray-800">
                                                            Email
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-gray-800">
                                                            Position
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-gray-800">
                                                            College
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-gray-800">
                                                            Department
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-gray-800">
                                                            Action
                                                        </span>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-gray-200">
                                            <?php $__empty_1 = true; $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $position): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr class="bg-white hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                                    <?php echo e($position->user->name); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                                    <?php echo e($position->user->email); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                                    <?php echo e($position->panel_position); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                                    
                                                    <?php echo e($position->college->name ?? 'N/A'); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                                    
                                                    
                                                    <?php echo e($position->department->name ?? ($position->panel_position === 'Dean' ? 'N/A (Dean)' : 'N/A')); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <button wire:click="openEditModal(<?php echo e($position->id); ?>)"
                                                        class="text-gray-900 bg-yellow-400 hover:bg-yellow-500 rounded-lg px-3 py-1 text-sm font-medium">
                                                        Edit
                                                    </button>
                                                    <button wire:click="confirmDelete(<?php echo e($position->id); ?>)"
                                                        class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-8 text-gray-500 bg-white">
                                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    <p class="text-lg font-medium">No panels found</p>
                                                    <p class="text-sm mt-1">Create a new panel to get started</p>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <!-- End Table -->
                                    <div class="p-4">
                                        <?php echo e($positions->links()); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!-- End Table Section -->
            </div>

        </div>
    </div>

    <div x-data x-on:swal:confirm.window="
        const d = $event.detail;

        Swal.fire({
            title: d.title ?? 'Are you sure?',
            text: d.text ?? 'This action cannot be undone.',
            icon: d.icon ?? 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.deleteConfirmed(d.id);
            }
        });
    ">

        <?php if($showCreateModal): ?>
        <?php echo $__env->make('livewire.admin.modals.create-panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>

        <?php if($showEditModal): ?>
        <?php echo $__env->make('livewire.admin.modals.edit-panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\livewire\admin\panel-manager.blade.php ENDPATH**/ ?>