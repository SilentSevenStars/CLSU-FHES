<?php

namespace Database\Seeders;

use App\Models\College;
use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [

            'College of Agriculture' => [
                'Agricultural Economics',
                'Agronomy',
                'Soil Science',
                'Crop Protection',
                'Animal Science',
                'Agricultural Extension',
                'Agricultural Systems and Engineering',
            ],

            'College of Arts and Social Sciences' => [
                'English and Humanities',
                'Filipino',
                'Social Sciences',
                'Psychology',
                'Development Communication',
            ],

            'College of Business and Accountancy' => [
                'Accountancy',
                'Business Administration',
                'Management',
                'Entrepreneurship',
            ],

            'College of Education' => [
                'Elementary Education',
                'Secondary Education',
                'Physical Education',
                'Graduate Studies in Education',
            ],

            'College of Engineering' => [
                'Agricultural and Biosystems Engineering',
                'Civil Engineering',
                'Computer Engineering',
                'Electrical Engineering',
                'Electronics Engineering',
                'Mechanical Engineering',
                'Engineering Sciences',
            ],

            'College of Fisheries' => [
                'Aquaculture',
                'Aquatic Resources Ecology and Management',
                'Aquatic Post-Harvest Technology',
                'Fisheries Extension',
            ],

            'College of Home Science and Industry' => [
                'Food Technology',
                'Hospitality Management',
                'Tourism Management',
                'Fashion and Textile Technology',
                'Home Economics Education',
            ],

            'College of Science' => [
                'Biology',
                'Chemistry',
                'Mathematics',
                'Physics',
                'Statistics',
                'Environmental Science',
                'Information Technology',
                'Information Systems',
                'Computer Science',
            ],

            'College of Veterinary Science and Medicine' => [
                'Basic Veterinary Sciences',
                'Paraclinical Veterinary Sciences',
                'Clinical Veterinary Sciences',
            ],
        ];

        foreach ($departments as $collegeName => $deptList) {

            $college = College::where('name', $collegeName)->first();

            if (! $college) {
                continue;
            }

            foreach ($deptList as $dept) {
                Department::firstOrCreate([
                    'name'       => $dept,
                    'college_id' => $college->id,
                ]);
            }
        }
    }
}
