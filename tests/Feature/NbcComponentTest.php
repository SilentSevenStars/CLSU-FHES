<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use App\Livewire\Admin\Nbc;
use App\Models\Applicant;
use App\Models\Position;
use App\Models\JobApplication;
use App\Models\Evaluation;
use App\Models\EducationalQualification;
use App\Models\ExperienceService;
use App\Models\ProfessionalDevelopment;
use App\Models\NbcAssignment;

class NbcComponentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * The component should handle a position without an associated college.
     * Previously this scenario raised a "property \"name\" on null" error.
     */
    public function test_load_nbc_data_handles_missing_college()
    {
        // create a user and applicant (applicant table requires user_id)
        $user = \App\Models\User::factory()->create();

        $applicant = Applicant::create([
            'first_name' => 'John',
            'middle_name' => 'Q',
            'last_name' => 'Public',
            'phone_number' => '1234',
            'user_id' => $user->id,
        ]);

        // create a position but do NOT assign a college
        $position = Position::create([
            'name' => 'Test Position',
            'specialization' => 'General',
            'education' => 'None',
            'experience' => 0,
            'training' => 0,
            'eligibility' => 'None',
        ]);

        // create job application linking them
        $application = JobApplication::create([
            'applicant_id' => $applicant->id,
            'position_id'  => $position->id,
            'status'       => 'approve',
            'present_position' => 'None',
            'education'        => 'None',
            'experience'       => 0,
            'training'         => 0,
            'eligibility'      => 'None',
            'other_involvement'=> '',
            'requirements_file'=> '',
        ]);

        // evaluation corresponding to job application
        $interviewDate = now()->format('Y-m-d');
        $evaluation = Evaluation::create([
            'job_application_id' => $application->id,
            'interview_date'     => $interviewDate,
            'interview_room'     => 'Room 101',
        ]);

        // create minimal NBC component scores and a complete assignment so resolveComponentScores returns data
        $edu = EducationalQualification::create(['subtotal' => 5]);
        $exp = ExperienceService::create(['subtotal' => 5]);
        $prof = ProfessionalDevelopment::create(['subtotal' => 5]);

        NbcAssignment::create([
            'evaluation_id'                  => $evaluation->id,
            'educational_qualification_id'   => $edu->id,
            'experience_service_id'          => $exp->id,
            'professional_development_id'    => $prof->id,
            'status'                         => 'complete',
            'evaluation_date'                => $interviewDate,
        ]);

        // now exercise the Livewire component
        $component = Livewire::test(Nbc::class)
            ->set('applicantId', $applicant->id)
            ->set('selectedPosition', $position->name)
            ->set('selectedInterviewDate', $interviewDate)
            ->call('loadNbcData');

        // ensure nbcData was populated and college field is safe (empty string)
        $data = $component->get('nbcData')[0];
        $this->assertSame('', $data['college']);
        $this->assertSame($position->name, $data['position']);
        $this->assertEquals($applicant->first_name . ' ' . $applicant->middle_name . ' ' . $applicant->last_name, $data['name']);
    }
}
