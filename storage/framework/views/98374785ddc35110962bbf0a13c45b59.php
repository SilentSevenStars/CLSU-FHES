<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo e(config('app.name', 'Laravel')); ?></title>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
<?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
<?php endif; ?>
<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>

<body class="bg-gray-50">
<!-- Mobile menu button -->
<?php echo $__env->make('components.mobile-view', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Sidebar -->
<?php if(Auth::user()->role === 'admin'): ?>
    <?php echo $__env->make('components.nav-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php elseif(Auth::user()->role === 'panel'): ?>
    <?php echo $__env->make('components.nav-panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php elseif(Auth::user()->role === 'nbc'): ?>
    <?php echo $__env->make('components.nav-nbc', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php else: ?>
    <?php echo $__env->make('components.nav-applicant', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>

<!-- Main content -->
<?php if(in_array(Auth::user()->role, ['applicant', 'admin', 'panel', 'nbc'])): ?>
<main class="p-4 sm:ml-16 min-h-screen">
    <?php echo e($slot); ?>

</main>
<?php else: ?>
<main class="p-4 sm:ml-64 min-h-screen">
    <?php echo e($slot); ?>

</main>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

<script src="<?php echo e(asset('js/sweetalert.js')); ?>"></script>
<script src="<?php echo e(asset('js/chart.js')); ?>"></script>
<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\components\layouts\app.blade.php ENDPATH**/ ?>