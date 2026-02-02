<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #222; }
        .header {
            background: linear-gradient(90deg, #3b82f6 0%, #06b6d4 100%); 
            color: white;
            padding: 10px 16px;
            margin-bottom: 12px;
            border-radius: 4px;
        }
        h3 { margin: 0 0 8px 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        th, td { border: 1px solid #000; padding: 6px 8px; vertical-align: top; }
        th {
            background: #D7F4D0; 
            font-weight: bold;
            text-align: left;
        }
        .small { font-size: 10px; color: #444; }
        .center { text-align: center; }
    </style>
</head>
<body>

<div class="header">
    <h3>FACULTY HIRING – <?php echo e($position->name ?? ''); ?></h3>
    <div class="small">Generated: <?php echo e(\Carbon\Carbon::now()->format('F d, Y')); ?></div>
</div>

<table>
    <thead>
        <tr>
            <th>Position</th>
            <th>Present Position</th>
            <th>Education</th>
            <th>Experience</th>
            <th>Training</th>
            <th>Eligibility</th>
            <th>Other Involvement</th>
            <th>CP Number</th>
            <th>Address</th>
            <th>Email Address</th>
        </tr>
    </thead>

    <tbody>
        <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($a->position_name); ?></td>
            <td><?php echo e($a->present_position); ?></td>
            <td><?php echo e($a->education); ?></td>
            <td class="center"><?php echo e($a->experience); ?></td>
            <td><?php echo e($a->training); ?></td>
            <td><?php echo e($a->eligibility); ?></td>
            <td><?php echo e($a->other_involvement); ?></td>
            <td><?php echo e($a->phone_number); ?></td>
            <td><?php echo e($a->address); ?></td>
            <td><?php echo e($a->email); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\export\scheduled-applicant-pdf.blade.php ENDPATH**/ ?>