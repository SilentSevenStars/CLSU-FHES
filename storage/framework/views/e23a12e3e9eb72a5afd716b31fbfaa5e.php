<div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
    <div class="relative p-6 w-full max-w-xl bg-gray-100 rounded-lg shadow-xl border border-gray-300">

        <h2 class="text-lg font-semibold mb-4 text-[#0a6025]">Edit Panel</h2>

        <form wire:submit.prevent="update">

            
            <label class="block text-sm font-medium mb-1 text-gray-700">Name</label>
            <input type="text" wire:model="name"
                class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0a6025] focus:border-gray-400"
                placeholder="Enter full name">
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            
            <label class="block mt-4 text-sm font-medium mb-1 text-gray-700">Email</label>
            <input type="email" wire:model="email" disabled
                class="w-full px-4 py-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed">
            <span class="text-xs text-gray-600">Email cannot be changed.</span>

            
            <label class="block mt-4 text-sm font-medium mb-1 text-gray-700">Panel Position</label>
            <select wire:model.live="panel_position" 
                class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0a6025] focus:border-gray-400">
                <option value="">-- Select Position --</option>
                <option value="Head">Head</option>
                <option value="Dean">Dean</option>
                <option value="Senior">Senior</option>
            </select>
            <?php $__errorArgs = ['panel_position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            
            <label class="block mt-4 text-sm font-medium mb-1 text-gray-700">College</label>
            
            <select wire:model.live="college_id" 
                class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400">
                <option value="">-- Select College --</option>
                <?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <option value="<?php echo e($col->id); ?>"><?php echo e($col->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['college_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            
            <label class="block mt-4 text-sm font-medium mb-1 text-gray-700">Department</label>
            <select wire:model="department_id" 
                class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400" 
                <?php if($panel_position === 'Dean' || !$college_id): ?> disabled <?php endif; ?>>

                <?php if($panel_position === 'Dean'): ?>
                    <option value="">None (Dean position)</option>
                <?php elseif(!$college_id): ?>
                    <option value="">Please choose a college first</option>
                <?php else: ?>
                    <option value="">-- Select Department --</option>
                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <option value="<?php echo e($dep->id); ?>"><?php echo e($dep->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </select>
            <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <!-- Buttons -->
            <div class="flex justify-end mt-6 space-x-3">
                <button type="button" wire:click="$set('showEditModal', false)"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition">
                    Cancel
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-[#0D7A2F] hover:bg-[#0A6025] text-white rounded-lg font-medium transition">
                    Save Changes
                </button>
            </div>

        </form>
    </div>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\livewire\admin\modals\edit-panel.blade.php ENDPATH**/ ?>