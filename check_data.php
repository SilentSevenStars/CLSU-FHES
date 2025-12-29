<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Applicant;

$count = Applicant::with(['jobApplications' => function($q){
    $q->where('status','approve')->with('evaluation');
}])->whereHas('jobApplications', function($q){
    $q->where('status','approve')->whereHas('evaluation', function($qq){
        $qq->whereDate('interview_date', '<', now());
    });
})->count();

echo "Count: $count\n";
?>