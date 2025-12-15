<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Applicant;
use App\Models\Position;
use App\Models\JobApplication;
use App\Models\Evaluation;
use App\Models\Panel;

class DemoApplicantsAndPanelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear related tables (use with caution)
        // DB::table('panel_assignments')->truncate(); // keep assignments if you want

        // Ensure positions exist for the requested colleges/departments
        $positionsMap = [
            'College of Engineering' => [
                'Information Technology',
                'Civil Engineering',
                'Engineering Science',
            ],
            'College of Agriculture' => [
                'Agricultural Economics',
                'Agronomy',
                'Soil Science',
            ],
            'College of Science' => [
                'Biology',
                'Mathematics and Physics',
                'Chemistry',
            ],
        ];

        $positions = [];
        foreach ($positionsMap as $college => $depts) {
            foreach ($depts as $dept) {
                $position = Position::firstOrCreate([
                    'name' => "Faculty - $dept",
                    'college' => $college,
                    'department' => $dept,
                ], [
                    'status' => 'vacant',
                    'start_date' => now()->subDays(10)->toDateString(),
                    'end_date' => now()->addDays(30)->toDateString(),
                ]);
                $positions[] = $position;
            }
        }

        // Create panel users: Deans for Agriculture and Science
        $panelsToCreate = [
            ['name' => 'Dean Agriculture', 'email' => 'dean.agri@example.test', 'panel_position' => 'dean', 'college' => 'College of Agriculture', 'department' => 'Agricultural Economics'],
            ['name' => 'Dean Science', 'email' => 'dean.science@example.test', 'panel_position' => 'dean', 'college' => 'College of Science', 'department' => 'Biology'],
        ];

        // Create heads and seniors in the specified departments
        $headsAndSeniors = [
            // College of Engineering
            ['name' => 'Head Civil Eng', 'email' => 'head.civil@example.test', 'panel_position' => 'head', 'college' => 'College of Engineering', 'department' => 'Civil Engineering'],
            ['name' => 'Senior Civil Eng', 'email' => 'senior.civil@example.test', 'panel_position' => 'senior', 'college' => 'College of Engineering', 'department' => 'Civil Engineering'],
            ['name' => 'Head Engineering Science', 'email' => 'head.engsci@example.test', 'panel_position' => 'head', 'college' => 'College of Engineering', 'department' => 'Engineering Science'],
            ['name' => 'Senior Engineering Science', 'email' => 'senior.engsci@example.test', 'panel_position' => 'senior', 'college' => 'College of Engineering', 'department' => 'Engineering Science'],

            // College of Agriculture
            ['name' => 'Head Agricultural Econ', 'email' => 'head.ageco@example.test', 'panel_position' => 'head', 'college' => 'College of Agriculture', 'department' => 'Agricultural Economics'],
            ['name' => 'Senior Agricultural Econ', 'email' => 'senior.ageco@example.test', 'panel_position' => 'senior', 'college' => 'College of Agriculture', 'department' => 'Agricultural Economics'],
            ['name' => 'Head Agronomy', 'email' => 'head.agronomy@example.test', 'panel_position' => 'head', 'college' => 'College of Agriculture', 'department' => 'Agronomy'],
            ['name' => 'Senior Agronomy', 'email' => 'senior.agronomy@example.test', 'panel_position' => 'senior', 'college' => 'College of Agriculture', 'department' => 'Agronomy'],
            ['name' => 'Head Soil Science', 'email' => 'head.soil@example.test', 'panel_position' => 'head', 'college' => 'College of Agriculture', 'department' => 'Soil Science'],
            ['name' => 'Senior Soil Science', 'email' => 'senior.soil@example.test', 'panel_position' => 'senior', 'college' => 'College of Agriculture', 'department' => 'Soil Science'],

            // College of Science
            ['name' => 'Head Biology', 'email' => 'head.bio@example.test', 'panel_position' => 'head', 'college' => 'College of Science', 'department' => 'Biology'],
            ['name' => 'Senior Biology', 'email' => 'senior.bio@example.test', 'panel_position' => 'senior', 'college' => 'College of Science', 'department' => 'Biology'],
            ['name' => 'Head MathPhys', 'email' => 'head.mathphys@example.test', 'panel_position' => 'head', 'college' => 'College of Science', 'department' => 'Mathematics and Physics'],
            ['name' => 'Senior MathPhys', 'email' => 'senior.mathphys@example.test', 'panel_position' => 'senior', 'college' => 'College of Science', 'department' => 'Mathematics and Physics'],
            ['name' => 'Head Chemistry', 'email' => 'head.chem@example.test', 'panel_position' => 'head', 'college' => 'College of Science', 'department' => 'Chemistry'],
            ['name' => 'Senior Chemistry', 'email' => 'senior.chem@example.test', 'panel_position' => 'senior', 'college' => 'College of Science', 'department' => 'Chemistry'],
        ];

        $panelUsers = array_merge($panelsToCreate, $headsAndSeniors);

        foreach ($panelUsers as $p) {
            $user = User::firstOrCreate(
                ['email' => $p['email']],
                ['name' => $p['name'], 'password' => Hash::make('12345678'), 'role' => 'panel']
            );

            Panel::firstOrCreate([
                'user_id' => $user->id,
            ], [
                'panel_position' => $p['panel_position'],
                'college' => $p['college'],
                'department' => $p['department'],
            ]);
        }

        // Create 60 applicants and job applications
        $colleges = array_keys($positionsMap);
        $allDepts = [];
        foreach ($positionsMap as $college => $depts) {
            foreach ($depts as $dept) {
                $allDepts[$college][] = $dept;
            }
        }

        $statuses = array_merge(array_fill(0,20,'pending'), array_fill(0,20,'approve'), array_fill(0,20,'decline'));

        for ($i = 1; $i <= 60; $i++) {
            $name = "Applicant {$i}";
            $email = "applicant{$i}@example.test";

            $user = User::firstOrCreate(['email' => $email], [
                'name' => $name,
                'password' => Hash::make('password'),
                'role' => 'applicant'
            ]);

            $applicant = Applicant::firstOrCreate(['user_id' => $user->id], [
                'first_name' => $name,
                'middle_name' => 'M',
                'last_name' => 'Lastname',
                'phone_number' => '09123456789',
                'address' => '123 Sample St',
            ]);

            // choose college and department round-robin
            $collegeIndex = ($i - 1) % count($colleges);
            $college = $colleges[$collegeIndex];
            $deptList = $positionsMap[$college];
            $dept = $deptList[($i - 1) % count($deptList)];

            // Ensure position exists for chosen college/dept
            $position = Position::firstOrCreate([
                'college' => $college,
                'department' => $dept,
                'name' => "Faculty - $dept",
            ], [
                'status' => 'vacant',
                'start_date' => now()->subDays(10)->toDateString(),
                'end_date' => now()->addDays(30)->toDateString(),
            ]);

            $status = $statuses[$i-1];

            $jobApp = JobApplication::updateOrCreate([
                'applicant_id' => $applicant->id,
                'position_id' => $position->id,
            ], [
                'present_position' => 'None',
                'education' => 'BS',
                'experience' => 0,
                'training' => 'None',
                'eligibility' => 'None',
                'other_involvement' => 'None',
                // use a dummy file path so DB NOT NULL constraint is satisfied
                'requirements_file' => 'requirements/dummy.pdf',
                'status' => $status,
                'reviewed_at' => now(),
            ]);

            // For approved ones, create evaluation with today's date & room
            if ($status === 'approve') {
                Evaluation::create([
                    'interview_date' => now()->toDateString(),
                    'interview_room' => 'CLIRDEC ITLEC 221',
                    'total_score' => 0,
                    'rank' => 0,
                    'job_application_id' => $jobApp->id,
                ]);
            }
        }

        $this->command->info('Seeded 60 applicants and panels.');
    }
}
