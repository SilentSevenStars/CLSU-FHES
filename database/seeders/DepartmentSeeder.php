<?php

namespace Database\Seeders;

use App\Models\College;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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

            'College of Arts and Sciences' => [
                'Biology',
                'Chemistry',
                'Mathematics',
                'Physics',
                'Statistics',
                'Environmental Science',
                'English and Humanities',
                'Filipino',
                'Social Sciences',
                'Psychology',
                'Development Communication',
            ],

            'College of Business Administration and Accountancy' => [
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

            'College of Human Ecology' => [
                'Human Development',
                'Family Life and Child Development',
                'Community and Environmental Resource Planning',
            ],

            'College of Information Technology' => [
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

            // Skip if college not found (safety)
            if (! $college) {
                continue;
            }

            foreach ($deptList as $dept) {
                Department::create([
                    'name'       => $dept,
                    'college_id' => $college->id,
                ]);
            }
        }
    }
}
