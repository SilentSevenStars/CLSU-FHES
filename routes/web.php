<?php

use App\Http\Controllers\ScreeningExportController;
use App\Livewire\Admin\Applicant;
use App\Livewire\Admin\ApplicantEdit;
use App\Livewire\Admin\ApplicantShow;
use App\Livewire\Admin\AssignPosition;
use App\Livewire\Admin\CollegeManager;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\DepartmentManager;
use App\Livewire\Admin\Message as AdminMessage;
use App\Livewire\Admin\Nbc;
use App\Livewire\Admin\NbcCommitteeManager;
use App\Livewire\Admin\NotificationManager as AdminNotificationManager;
use App\Livewire\Admin\PanelManager;
use App\Livewire\Admin\Position\PositionCreate;
use App\Livewire\Admin\Position\PositionEdit;
use App\Livewire\Admin\Position\PositionIndex;
use App\Livewire\Admin\PositionRankManager;
use App\Livewire\Admin\RepresentativeManager;
use App\Livewire\Admin\ScheduledApplicant;
use App\Livewire\Admin\Screening;
use App\Livewire\Applicant\ApplicantMessage;
use App\Livewire\Applicant\ApplicantNotification;
use App\Livewire\Applicant\ApplicantNotifications;
use App\Livewire\Applicant\ApplyJob;
use App\Livewire\Applicant\Dashboard;
use App\Livewire\Applicant\EditJobApplication;
use App\Livewire\Applicant\JobApplication;
use App\Livewire\Message;
use App\Livewire\Nbc\Dashboard as NbcDashboard;
use App\Livewire\Nbc\EducationalQualificationForm;
use App\Livewire\Nbc\ExperienceServiceForm;
use App\Livewire\Nbc\NbcForm;
use App\Livewire\Nbc\ProfessionalDevelopmentForm;
use App\Livewire\NotificationManager;
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
    // 'verified',
    'role:applicant'
])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/apply-job', ApplyJob::class)->name('apply-job');
    Route::get('/job-application/{position_id}', JobApplication::class)->name('job-application');
    Route::get('/applicant/edit-job-application/{application_id}', EditJobApplication::class)->name('edit-job-application');
    Route::get('/notifications', ApplicantNotification::class)->name('notifications');
    Route::get('/message/{notificationId}', ApplicantMessage::class)->name('message');
});

Route::middleware([
    'auth',
    'verified',
    'role:admin'
])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function(){
        Route::get('/', AdminDashboard::class)->name('dashboard');
        Route::get('/positions', PositionIndex::class)->name('position');
        Route::get('/positions/create', PositionCreate::class)->name('position.create');
        Route::get('/positions/{id}/edit', PositionEdit::class)->name('position.edit');
        Route::get('/applicants', Applicant::class)->name('applicant');
        Route::get('/applicants/{job_application_id}', ApplicantShow::class)->name('applicant.show');
        Route::get('/applicants/{job_application_id}/edit', ApplicantEdit::class)->name('applicant.edit');
        Route::get('/panel', PanelManager::class)->name('panel');
        Route::get('/scheduled-applicants', ScheduledApplicant::class)->name('scheduled');
        Route::get('/screening', Screening::class)->name('screening');
        Route::post('/screening/export', [ScreeningExportController::class, 'export'])->name('admin.screening.export');
        Route::get('/notifications', AdminNotificationManager::class)->name('notifications');
        Route::get('/message', AdminMessage::class)->name('message');
        Route::get('/position-rank', PositionRankManager::class)->name('position.rank');
        Route::get('/colleges', CollegeManager::class)->name('college');
        Route::get('/department', DepartmentManager::class)->name('department');
        Route::get('/assign-position', AssignPosition::class)->name('assign.position');
        Route::get('/nbc-comittee', NbcCommitteeManager::class)->name('nbc.comittee');
        Route::get('/nbc', Nbc::class)->name('nbc');
        Route::get('/representative', RepresentativeManager::class)->name('representative');
    });
    
});

Route::middleware([
    'auth',
    // 'verified',
    'role:panel'
])->group(function () {
    Route::get('/panel/', PanelDashboard::class)->name('panel.dashboard');
    Route::get('/panel/interview/{evaluationId}', Interview::class)->name('panel.interview');
    Route::get('/panel/performance/{evaluationId}/{interviewId}', Performance::class)->name('panel.performance');
    Route::get('/panel/experience/{evaluationId}', Experience::class)->name('panel.experience');
});

Route::middleware([
    'auth',
    // 'verified',
    'role:nbc'
])->group(function () {
    Route::prefix('/nbc')->name('nbc.')->group(function(){
        Route::get('/', NbcDashboard::class)->name('dashboard');
        Route::get('/educational-qualification/{evaluationId}', EducationalQualificationForm::class)->name('educational-qualification');
        Route::get('/experience-service/{evaluationId}', ExperienceServiceForm::class)->name('experience-service');
        Route::get('/professional-development/{evaluationId}', ProfessionalDevelopmentForm::class)->name('professional-development');
        Route::get('/evaluation/{evaluationId}', NbcForm::class)->name('evaluation');
    });
});


Route::view('/test', 'test');
