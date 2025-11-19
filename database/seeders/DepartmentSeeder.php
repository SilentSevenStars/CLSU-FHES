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
                'Agricultural Extension'
            ],

            'College of Arts and Social Sciences' => [
                'Psychology',
                'English and Humanities',
                'Filipino',
                'Social Sciences',
                'Development Communication'
            ],

            'College of Business and Accountancy' => [
                'Accountancy',
                'Business Administration'
            ],

            'College of Education' => [
                'Elementary Education',
                'Secondary Education',
                'Graduate Studies in Education'
            ],

            'College of Engineering' => [
                'Agricultural and Biosystems Engineering',
                'Civil Engineering',
                'Engineering Sciences',
                'Information Technology',
                'Information Systems'
            ],

            'College of Fisheries' => [
                'Aquaculture',
                'Aquatic Resources Ecology and Management',
                'Aquatic Post-Harvest'
            ],

            'College of Home Science and Industry' => [
                'Food Technology',
                'Hospitality and Tourism',
                'Fashion and Textile Technology'
            ],

            'College of Science' => [
                'Biology',
                'Mathematics and Physics',
                'Chemistry',
                'Environmental Science'
            ],

            'College of Veterinary Science and Medicine' => [
                'Basic Veterinary Sciences',
                'Paraclinical Sciences',
                'Clinical Sciences'
            ],

            'Distance, Open, and Transnational University (DOT-Uni)' => [
                'Distance Learning Programs'
            ],
        ];

        foreach ($departments as $collegeName => $deptList) {
            foreach ($deptList as $dept) {
                Department::create([
                    'name'    => $dept,
                    'college' => $collegeName
                ]);
            }
        }
    }
}
