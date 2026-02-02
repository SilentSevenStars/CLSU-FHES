<div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8 animate-fadeIn">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold bg-[#1E7F3E] bg-clip-text text-transparent mb-2">
                        Assign Position
                    </h1>
                    <p class="text-gray-600 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#1E7F3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        Promote or hire applicants to their evaluated positions
                    </p>
                </div>
            </div>
        </div>

    <!-- Alert Modal -->
    <div x-data="{ show: <?php if ((object) ('showAlertModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showAlertModal'->value()); ?>')<?php echo e('showAlertModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showAlertModal'); ?>')<?php endif; ?> }" x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="alert-modal-title" role="dialog" aria-modal="true">

        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                @click="$wire.closeAlertModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <!-- Icon -->
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                            :class="{
                                'bg-green-100': '<?php echo e($alertType); ?>' === 'success',
                                'bg-red-100': '<?php echo e($alertType); ?>' === 'error'
                            }">
                            <!-- Success Icon -->
                            <svg x-show="'<?php echo e($alertType); ?>' === 'success'" class="h-6 w-6 text-green-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <!-- Error Icon -->
                            <svg x-show="'<?php echo e($alertType); ?>' === 'error'" class="h-6 w-6 text-red-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="alert-modal-title">
                                <span x-show="'<?php echo e($alertType); ?>' === 'success'" class="text-green-600">Success!</span>
                                <span x-show="'<?php echo e($alertType); ?>' === 'error'" class="text-red-600">Error!</span>
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    <?php echo e($alertMessage); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="closeAlertModal"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200"
                        :class="{
                            'bg-green-600 hover:bg-green-700 focus:ring-green-500': '<?php echo e($alertType); ?>' === 'success',
                            'bg-red-600 hover:bg-red-700 focus:ring-red-500': '<?php echo e($alertType); ?>' === 'error'
                        }">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">
            <!-- Table Header -->
            <div class="bg-[#1E7F3E] p-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                            <i class="fa-solid fa-user-check text-white text-lg"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white"><?php echo e($showArchived ? 'Archived Applications' : 'Applicants for Assignment'); ?></h2>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <select wire:model.live="perPage"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white focus:outline-none">
                            <option value="5">5 / page</option>
                            <option value="10">10 / page</option>
                            <option value="25">25 / page</option>
                            <option value="50">50 / page</option>
                            <option value="100">100 / page</option>
                        </select>

                        <!-- Toggle Archived/Active -->
                        <button wire:click="$set('showArchived', !$showArchived)"
                                class="inline-flex items-center px-4 py-2 <?php echo e($showArchived ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-blue-600 hover:bg-blue-700'); ?> text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 <?php echo e($showArchived ? 'focus:ring-yellow-300' : 'focus:ring-blue-300'); ?>">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <?php echo e($showArchived ? 'Show Active' : 'Show Archived'); ?>

                        </button>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                <div class="flex flex-col">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div class="bg-gray-50 border border-gray-300 rounded-xl shadow-2xs overflow-hidden">
                                <!-- Filters / Search Row -->
                                <div class="px-6 py-4 flex flex-wrap items-center justify-between border-b border-gray-300 gap-3">
                                    <div class="flex items-center gap-4 flex-wrap">
                                        <!-- Search Button -->
                                        <button wire:click="openSearchModal"
                                            class="inline-flex items-center px-4 py-2 bg-[#156B2D] hover:bg-[#125A26] text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-300 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search Applicant
                </button>

                                        <!-- Active Filters Display -->
                                        <?php if($search || $positionFilter): ?>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <?php if($search): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                                                Name: <?php echo e($search); ?>

                                                <button wire:click="$set('search', '')" class="ml-2 focus:outline-none">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </span>
                                            <?php endif; ?>
                                            <?php if($positionFilter): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                Position: <?php echo e($positionFilter); ?>

                                                <button wire:click="$set('positionFilter', '')" class="ml-2 focus:outline-none">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Table -->
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-200">
                    <tr>
                        <th scope="col"
                                            class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase text-black">Applicant Name</span>
                                        </th>
                        <th scope="col"
                                            class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase text-black">Current Position</span>
                                        </th>
                        <th scope="col"
                                            class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase text-black">Applied Position</span>
                                        </th>
                        <th scope="col"
                                            class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase text-black">Interview Date</span>
                                        </th>
                        <th scope="col"
                                            class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase text-black">Actions</span>
                                        </th>
                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-300 bg-gray-50">
                    <?php $__empty_1 = true; $__currentLoopData = $applicants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $applicant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $__currentLoopData = $applicant->jobApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($application->evaluation && ($application->archive == $showArchived)): ?>
                    <tr class="bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                <?php echo e($applicant->user->name); ?>

                            </div>
                            <div class="text-sm text-gray-500">
                                <?php echo e($applicant->user->email); ?>

                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                <?php echo e($applicant->position ?? 'None'); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                <?php echo e($application->position->name); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e(\Carbon\Carbon::parse($application->evaluation->interview_date)->format('M d, Y')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <?php if(!$showArchived): ?>
                                <button
                                    wire:click="openConfirmModal(<?php echo e($applicant->id); ?>, <?php echo e($application->evaluation->id); ?>)"
                                    class="bg-[#1E7F3E] hover:bg-[#156B2D] text-white px-4 py-2 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-300 focus:ring-offset-2">
                                    Assign Position
                                </button>
                                <button
                                    wire:click="openArchiveModal(<?php echo e($application->id); ?>)"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                                    title="Archive this application">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                </button>
                                <?php else: ?>
                                <button
                                    wire:click="unarchive(<?php echo e($application->id); ?>)"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2"
                                    title="Unarchive this application">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                    </svg>
                                    Unarchive
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                    </path>
                                </svg>
                                <p class="text-lg font-medium">No applicants found</p>
                                <p class="text-sm">Try adjusting your search or filter criteria</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                                    </tbody>
                                </table>

                                <!-- Pagination -->
                                <div class="p-4 bg-white border-t border-gray-300">
                                    <?php echo e($applicants->links()); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Search Modal -->
    <div x-data="{ show: <?php if ((object) ('showSearchModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showSearchModal'->value()); ?>')<?php echo e('showSearchModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showSearchModal'); ?>')<?php endif; ?> }" x-show="show" x-cloak
        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="search-modal-title" role="dialog" aria-modal="true">

        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                @click="$wire.closeSearchModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="search-modal-title">
                            Search & Filter Applicants
                        </h3>
                        <button wire:click="closeSearchModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Search Input with Dropdown -->
                        <div class="relative" x-data="{ open: <?php if ((object) ('showDropdown') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showDropdown'->value()); ?>')<?php echo e('showDropdown'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showDropdown'); ?>')<?php endif; ?> }">
                            <label for="searchInput" class="block text-sm font-medium text-gray-700 mb-2">
                                Search by Name
                            </label>
                            <input type="text" wire:model.live.debounce.300ms="searchInput" id="searchInput"
                                   placeholder="Type applicant name..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1E7F3E] focus:border-transparent"
                                   autocomplete="off">

                            <!-- Dropdown -->
                            <div x-show="open" x-cloak
                                class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto">
                                <?php if(!empty($filteredNames)): ?>
                                <?php $__currentLoopData = $filteredNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div wire:click="selectName('<?php echo e($name); ?>')"
                                     class="px-4 py-2 hover:bg-emerald-50 cursor-pointer transition-colors duration-150">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        <span class="text-sm text-gray-900"><?php echo e($name); ?></span>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                    No matching names found
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Position Filter -->
                        <div>
                            <label for="tempPositionFilter" class="block text-sm font-medium text-gray-700 mb-2">
                                Filter by Applied Position
                            </label>
                            <select wire:model="tempPositionFilter" id="tempPositionFilter"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1E7F3E] focus:border-transparent">
                                <option value="">All Positions</option>
                                <?php $__currentLoopData = $availablePositions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $position): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($position); ?>"><?php echo e($position); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="button" wire:click="applySearch"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#1E7F3E] text-base font-medium text-white hover:bg-[#156B2D] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-300 sm:w-auto sm:text-sm transition-colors duration-200">
                        Apply
                    </button>
                    <button type="button" wire:click="clearFilters"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200">
                        Clear All
                    </button>
                    <button type="button" wire:click="closeSearchModal"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div x-data="{ show: <?php if ((object) ('showConfirmModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showConfirmModal'->value()); ?>')<?php echo e('showConfirmModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showConfirmModal'); ?>')<?php endif; ?> }" x-show="show" x-cloak
        @click.away="console.log('clicked away')"
        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <!-- Background overlay -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Center modal -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Confirm Position Assignment
                            </h3>
                            <?php if($selectedApplicant && $selectedEvaluation): ?>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-3">
                                    Are you sure you want to assign the following position?
                                </p>
                                <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-700">Applicant:</span>
                                        <span class="text-sm text-gray-900"><?php echo e($selectedApplicant->user->name); ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-700">Current Position:</span>
                                        <span class="text-sm text-gray-900"><?php echo e($selectedApplicant->position ?? 'None'); ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-700">New Position:</span>
                                        <span class="text-sm font-bold text-green-700"><?php echo e($selectedEvaluation->jobApplication->position->name); ?></span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-3">
                                    * This will mark the applicant as hired and send an email notification
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="confirmAssignment" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="confirmAssignment">Confirm</span>
                        <span wire:loading wire:target="confirmAssignment" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                    <button type="button" wire:click="closeConfirmModal"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Archive Confirmation Modal -->
    <div x-data="{ show: <?php if ((object) ('showArchiveModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showArchiveModal'->value()); ?>')<?php echo e('showArchiveModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showArchiveModal'); ?>')<?php endif; ?> }" x-show="show" x-cloak
        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="archive-modal-title" role="dialog" aria-modal="true">

        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="archive-modal-title">
                                Archive Job Application
                            </h3>
                            <?php if($selectedJobApplication): ?>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-3">
                                    Are you sure you want to archive this job application?
                                </p>
                                <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-700">Applicant:</span>
                                        <span class="text-sm text-gray-900"><?php echo e($selectedJobApplication->applicant->user->name); ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-700">Position:</span>
                                        <span class="text-sm text-gray-900"><?php echo e($selectedJobApplication->position->name); ?></span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-3">
                                    * Archived applications will not be visible in this list and cannot be assigned a position
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="confirmArchive" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="confirmArchive">Archive</span>
                        <span wire:loading wire:target="confirmArchive" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Archiving...
                        </span>
                    </button>
                    <button type="button" wire:click="closeArchiveModal"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    </div>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\livewire\admin\assign-position.blade.php ENDPATH**/ ?>