<div class="p-6">

    <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>

    <div class="bg-white p-5 rounded-lg shadow mb-10">
        <h2 class="text-xl font-semibold mb-3">Applicants per Position (<?php echo e($currentMonthName); ?>, <?php echo e($currentYear); ?>)</h2>
        <canvas id="positionChart" height="120"></canvas>
    </div>

    <div class="bg-white p-5 rounded-lg shadow mb-10">
        <h2 class="text-xl font-semibold mb-3">Applicants per College (<?php echo e($currentMonthName); ?>, <?php echo e($currentYear); ?>)</h2>
        <canvas id="collegeChart" height="120"></canvas>
    </div>

</div>



<script>
document.addEventListener('livewire:init', function () {

    new Chart(document.getElementById('positionChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($positionLabels, 15, 512) ?>,
            datasets: [{
                label: 'Applicants',
                data: <?php echo json_encode($positionCounts, 15, 512) ?>,
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderColor: 'rgba(67, 56, 202, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('collegeChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($collegeLabels, 15, 512) ?>,
            datasets: [{
                label: 'Applicants',
                data: <?php echo json_encode($collegeCounts, 15, 512) ?>,
                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                borderColor: 'rgba(5, 150, 105, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });

});
</script><?php /**PATH C:\Users\Owner\Desktop\projects\CLSU CAPS\resources\views/livewire/admin/dashboard.blade.php ENDPATH**/ ?>