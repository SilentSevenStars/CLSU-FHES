<div class="flex-1 bg-gradient-to-br from-slate-50 to-green-50 p-6 overflow-auto min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Page header -->
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-[#0A6025] mb-2">Admin Dashboard</h1>
            <p class="text-gray-600 text-sm">
                Overview of applicants per position and per college for {{ $currentMonthName }}, {{ $currentYear }}.
            </p>
        </div>

        <!-- Charts -->
        <div class="bg-white p-6 rounded-xl shadow-lg mb-10">
            <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-1">
                Applicants per Position
                <span class="block text-sm font-normal text-gray-500">
                    {{ $currentMonthName }}, {{ $currentYear }}
                </span>
            </h2>
            <canvas id="positionChart" height="120"></canvas>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg mb-10">
            <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-1">
                Applicants per College
                <span class="block text-sm font-normal text-gray-500">
                    {{ $currentMonthName }}, {{ $currentYear }}
                </span>
            </h2>
            <canvas id="collegeChart" height="120"></canvas>
        </div>

    </div>
</div>

<script>
document.addEventListener('livewire:init', function () {

    new Chart(document.getElementById('positionChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($positionLabels),
            datasets: [{
                label: 'Applicants',
                data: @json($positionCounts),
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderColor: 'rgba(67, 56, 202, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('collegeChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($collegeLabels),
            datasets: [{
                label: 'Applicants',
                data: @json($collegeCounts),
                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                borderColor: 'rgba(5, 150, 105, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });

});
</script>