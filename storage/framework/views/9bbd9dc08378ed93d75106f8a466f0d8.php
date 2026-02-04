<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="flex-1 flex flex-col justify-center items-center bg-white px-6 sm:px-8 lg:px-12 xl:px-16">
        <div class="w-full max-w-md">
            <div class="w-full">
                <!-- Title -->
                <div class="mb-10">
                    <h1 class="text-4xl sm:text-5xl font-bold text-[#0B712C] leading-tight">
                        <span class="block">Faculty Hiring</span>
                        <span class="block">Evaluation</span>
                        <span class="block">System</span>
                    </h1>
                </div>

                <?php if(session('status') == 'verification-link-sent'): ?>
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-green-800 leading-5 tracking-wide">
                        <?php echo e(__('A new verification link has been sent to the email address you provided in your
                        profile settings.')); ?>

                    </p>
                </div>
                <?php endif; ?>

                <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <p class="text-sm text-gray-700 leading-6">
                        <?php echo e(__('Before continuing, please verify your email address by clicking the link we just
                        emailed to you. If you didn\'t receive the email, you can request another below.')); ?>

                    </p>
                </div>

                <div class="flex items-center justify-between">
                    <form method="POST" action="<?php echo e(route('verification.send')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="py-3 px-6 bg-[#0A6025] hover:bg-[#0B712C] text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0B712C]">
                            <?php echo e(__('Resend Verification Email')); ?>

                        </button>
                    </form>

                    <div class="flex items-center gap-4">
                        <a href="<?php echo e(route('profile.show')); ?>"
                            class="text-sm font-semibold text-[#0B712C] hover:text-[#0A6025] transition duration-150 no-underline">
                            <?php echo e(__('Edit Profile')); ?>

                        </a>

                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                class="text-sm font-semibold text-[#0B712C] hover:text-[#0A6025] transition duration-150">
                                <?php echo e(__('Log Out')); ?>

                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>