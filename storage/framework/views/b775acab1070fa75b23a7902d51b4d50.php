<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php endif; ?>

    <!-- Styles -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>

<body>
    <div class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex">
            <!-- Left Side - Card (match login/register look) -->
            <?php echo e($slot); ?>


            <!-- Right Side - CLSU Image with Curved Shape (same as login/register) -->
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
                <div class="w-full h-full rounded-[150px_0_0_100px] overflow-hidden">
                    <img src="<?php echo e(asset('image/clsu.jpg')); ?>" alt="CLSU Campus" class="w-full h-full object-cover"
                        onerror="this.src='<?php echo e(asset('image/clsu-logo-green.png')); ?>'; this.onerror=null; this.style.objectFit='contain'; this.style.padding='2rem';">
                </div>
            </div>
        </div>
    </div>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>

</html><?php /**PATH C:\Users\Owner\Desktop\projects\CLSU CAPS\resources\views/layouts/guest.blade.php ENDPATH**/ ?>