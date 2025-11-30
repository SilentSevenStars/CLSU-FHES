<?php

use App\Http\Controllers\ScreeningExportController;
use App\Livewire\Admin\Applicant;
use App\Livewire\Admin\ApplicantShow;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\PanelManager;
use App\Livewire\Admin\PositionManager;
use App\Livewire\Admin\ScheduledApplicant;
use App\Livewire\Admin\Screening;
use App\Livewire\Applicant\ApplyJob;
use App\Livewire\Applicant\Dashboard;
use App\Livewire\Applicant\JobApplication;
use App\Livewire\Panel\Dashboard as PanelDashboard;
use App\Livewire\Panel\Experience;
use App\Livewire\Panel\Interview;
use App\Livewire\Panel\Performance;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

Route::middleware([
    'auth',
    'verified',
    'role:applicant'
])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/apply-job', ApplyJob::class)->name('apply-job');
    Route::get('/job-application/{position_id}', JobApplication::class)->name('job-application');
});

Route::middleware([
    'auth',
    'verified',
    'role:admin'
])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function(){
        Route::get('/', AdminDashboard::class)->name('dashboard');
        Route::get('/position', PositionManager::class)->name('position');
        Route::get('/applicants', Applicant::class)->name('applicant');
        Route::get('/applicants/{job_application_id}', ApplicantShow::class)->name('applicant.show');
        Route::get('/panel', PanelManager::class)->name('panel');
        Route::get('/scheduled-applicants', ScheduledApplicant::class)->name('scheduled');
        Route::get('/screening', Screening::class)->name('screening');
        Route::post('/screening/export', [ScreeningExportController::class, 'export'])
        ->name('admin.screening.export');
    });
    
});

Route::middleware([
    'auth',
    'verified',
    'role:panel'
])->group(function () {
    Route::get('/panel/', PanelDashboard::class)->name('panel.dashboard');
    Route::get('/panel/interview/{evaluationId}', Interview::class)->name('panel.interview');
    Route::get('/panel/performance/{evaluationId}/{interviewId}', Performance::class)->name('panel.performance');
    Route::get('/panel/experience/{evaluationId}', Experience::class)->name('panel.experience');
});


Route::view('/test', 'test');
