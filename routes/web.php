<?php

use App\Http\Controllers\ScreeningExportController;
use App\Livewire\Admin\Applicant;
use App\Livewire\Admin\ApplicantEdit;
use App\Livewire\Admin\ApplicantShow;
use App\Livewire\Admin\ArchiveUserManagement;
use App\Livewire\Admin\AssignPosition;
use App\Livewire\Admin\ArchiveApplicantManagement;
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
use App\Livewire\Admin\Profile as AdminProfile;
use App\Livewire\Admin\ProfileView as AdminProfileView;
use App\Livewire\Admin\RepresentativeManager;
use App\Livewire\Admin\RolePermissionManager;
use App\Livewire\Admin\ScheduledApplicant;
use App\Livewire\Admin\Screening;
use App\Livewire\Admin\UpdatePassword as AdminUpdatePassword;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Applicant\ApplicantMessage;
use App\Livewire\Applicant\ApplicantNotification;
use App\Livewire\Applicant\ApplicantNotifications;
use App\Livewire\Applicant\ApplyJob;
use App\Livewire\Applicant\Dashboard;
use App\Livewire\Applicant\EditJobApplication;
use App\Livewire\Applicant\JobApplication;
use App\Livewire\Applicant\Profile;
use App\Livewire\Applicant\ProfileView;
use App\Livewire\Applicant\UpdatePassword;
use App\Livewire\Message;
use App\Livewire\Nbc\Dashboard as NbcDashboard;
use App\Livewire\Nbc\EducationalQualificationForm;
use App\Livewire\Nbc\ExperienceServiceForm;
use App\Livewire\Nbc\NbcForm;
use App\Livewire\Nbc\ProfessionalDevelopmentForm;
use App\Livewire\Nbc\Profile as NbcProfile;
use App\Livewire\Nbc\ProfileView as NbcProfileView;
use App\Livewire\Nbc\UpdatePassword as NbcUpdatePassword;
use App\Livewire\NotificationManager;
use App\Livewire\Panel\Dashboard as PanelDashboard;
use App\Livewire\Panel\Experience;
use App\Livewire\Panel\Interview;
use App\Livewire\Panel\Performance;
use App\Livewire\Panel\Profile as PanelProfile;
use App\Livewire\Panel\ProfileView as PanelProfileView;
use App\Livewire\Panel\UpdatePassword as PanelUpdatePassword;
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
    Route::get('/applicant/edit-job-application/{application_id}', EditJobApplication::class)->name('edit-job-application');
    Route::get('/notifications', ApplicantNotification::class)->name('notifications');
    Route::get('/message/{notificationId}', ApplicantMessage::class)->name('message');
    Route::get('/profile-view', ProfileView::class)->name('profile-view');
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/update-password', UpdatePassword::class)->name('update-password');
});

Route::middleware([
    'auth',
    // 'verified',
    'role:admin|super-admin'
])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {

        // --- Dashboard (no permission gate needed) ---
        Route::get('/', AdminDashboard::class)->name('dashboard');

        // --- Positions ---
        Route::get('/positions', PositionIndex::class)
            ->name('position')
            ->middleware('permission:position.view');
        Route::get('/positions/create', PositionCreate::class)
            ->name('position.create')
            ->middleware('permission:position.create');
        Route::get('/positions/{id}/edit', PositionEdit::class)
            ->name('position.edit')
            ->middleware('permission:position.edit');

        // --- Applicants ---
        Route::get('/applicants', Applicant::class)
            ->name('applicant')
            ->middleware('permission:applicant.view');
        Route::get('/applicants/{job_application_id}', ApplicantShow::class)
            ->name('applicant.show')
            ->middleware('permission:applicant.view');
        Route::get('/applicants/{job_application_id}/edit', ApplicantEdit::class)
            ->name('applicant.edit')
            ->middleware('permission:applicant.edit');

        // --- Panel (CRUD) ---
        Route::get('/panel', PanelManager::class)
            ->name('panel')
            ->middleware('permission:panel.view');

        // --- Scheduled Applicants ---
        Route::get('/scheduled-applicants', ScheduledApplicant::class)
            ->name('scheduled')
            ->middleware('permission:applicant.scheduled');

        // --- Screening ---
        Route::get('/screening', Screening::class)
            ->name('screening')
            ->middleware('permission:screening.view');
        // Route::post('/screening/export', [ScreeningExportController::class, 'export'])
        //     ->name('screening.export')
        //     ->middleware('permission:screening.export');

        // --- Notifications & Messages ---
        Route::get('/notifications', AdminNotificationManager::class)
            ->name('notifications')
            ->middleware('permission:notification');
        Route::get('/message', AdminMessage::class)
            ->name('message')
            ->middleware('permission:message');

        // --- Position Rank (CRUD) ---
        Route::get('/position-rank', PositionRankManager::class)
            ->name('position.rank')
            ->middleware('permission:position-rank.view');

        // --- Colleges (CRUD) ---
        Route::get('/colleges', CollegeManager::class)
            ->name('college')
            ->middleware('permission:college.view');

        // --- Departments (CRUD) ---
        Route::get('/department', DepartmentManager::class)
            ->name('department')
            ->middleware('permission:department.view');

        // --- Assign Position ---
        Route::get('/assign-position', AssignPosition::class)
            ->name('assign.position')
            ->middleware('permission:assign-position.view');

        // --- Archive Applicants Management---
        Route::get('/archive-applicants', ArchiveApplicantManagement::class)
            ->name('applicant.archive')
            ->middleware('permission:assign.position.archive.view');

        // --- NBC Committee (CRUD) — TYPO FIXED here ---
        Route::get('/nbc-comittee', NbcCommitteeManager::class)
            ->name('nbc.comittee')
            ->middleware('permission:nbc-committee.view');  

        // --- NBC ---
        Route::get('/nbc', Nbc::class)
            ->name('nbc')
            ->middleware('permission:nbc.view');

        // --- Representatives (CRUD) ---
        Route::get('/representative', RepresentativeManager::class)
            ->name('representative')
            ->middleware('permission:representative.view');

        // --- User Management (CRUD) ---
        Route::get('/users', UserManagement::class)
            ->name('user')
            ->middleware('permission:user.view');

        Route::get('/roles-permissions', RolePermissionManager::class)
            ->name('role-permission')
            ->middleware('permission:role-permission.view');

        Route::get('/archive-user', ArchiveUserManagement::class)
            ->name('user.archive.view')
            ->middleware('permission:user.archive.view');

        Route::get('/profile-view', AdminProfileView::class)->name('profile-view');
        Route::get('/update-password', AdminUpdatePassword::class)->name('update-password');
        Route::get('/profile', AdminProfile::class)->name('profile');
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
    Route::get('/panel/profile-view', PanelProfileView::class)->name('panel.profile-view');
    Route::get('/panel/update-password', PanelUpdatePassword::class)->name('panel.update-password');
    Route::get('/panel/profile', PanelProfile::class)->name('panel.profile');
});

Route::middleware([
    'auth',
    // 'verified',
    'role:nbc'
])->group(function () {
    Route::prefix('/nbc')->name('nbc.')->group(function () {
        Route::get('/', NbcDashboard::class)->name('dashboard');
        Route::get('/educational-qualification/{evaluationId}', EducationalQualificationForm::class)->name('educational-qualification');
        Route::get('/experience-service/{evaluationId}', ExperienceServiceForm::class)->name('experience-service');
        Route::get('/professional-development/{evaluationId}', ProfessionalDevelopmentForm::class)->name('professional-development');
        Route::get('/evaluation/{evaluationId}', NbcForm::class)->name('evaluation');
        Route::get('/profile-view', NbcProfileView::class)->name('profile-view');
        Route::get('/update-password', NbcUpdatePassword::class)->name('update-password');
        Route::get('/profile', NbcProfile::class)->name('profile');
    });
});


Route::view('/test', 'test');
