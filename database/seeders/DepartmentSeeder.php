<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            // College of Agriculture (college_id: 1)
            ['name' => 'Agricultural Extension Education', 'college_id' => 1],
            ['name' => 'Agri-Management', 'college_id' => 1],
            ['name' => 'Soil Science', 'college_id' => 1],
            ['name' => 'Crop Science', 'college_id' => 1],
            ['name' => 'Crop Protection', 'college_id' => 1],
            ['name' => 'Animal Science', 'college_id' => 1],

            // College of Arts and Social Sciences (college_id: 2)
            ['name' => 'Development Communication', 'college_id' => 2],
            ['name' => 'Social Sciences', 'college_id' => 2],
            ['name' => 'English and Humanities', 'college_id' => 2],
            ['name' => 'Filipino', 'college_id' => 2],
            ['name' => 'Psychology', 'college_id' => 2],

            // College of Business Administration and Accountancy (college_id: 3)
            ['name' => 'Accountancy', 'college_id' => 3],
            ['name' => 'Business Administration', 'college_id' => 3],

            // College of Education (college_id: 4)
            ['name' => 'Elementary Education', 'college_id' => 4],
            ['name' => 'Secondary Education', 'college_id' => 4],
            ['name' => 'Advanced Studies in Education', 'college_id' => 4],
            ['name' => 'Institute of Sports, Physical Education and Recreation', 'college_id' => 4],
            ['name' => 'Department of Education Policy and Practice', 'college_id' => 4],
            ['name' => 'Department of Science Education', 'college_id' => 4],
            ['name' => 'Department of Language, Culture and Arts Education', 'college_id' => 4],
            ['name' => 'Department of Early Childhood and Elementary Education', 'college_id' => 4],
            ['name' => 'Department of Technology Livelihood and Life Skills Education', 'college_id' => 4],
            ['name' => 'Agricultural Science and Technology School', 'college_id' => 4],
            ['name' => 'University Science High School', 'college_id' => 4],
            ['name' => 'Center for Educational Resource and Development Services', 'college_id' => 4],

            // College of Engineering (college_id: 5)
            ['name' => 'Agricultural and Biosystems Engineering', 'college_id' => 5],
            ['name' => 'Civil Engineering', 'college_id' => 5],
            ['name' => 'Engineering Science', 'college_id' => 5],
            ['name' => 'Information Technology', 'college_id' => 5],

            // College of Fisheries (college_id: 6)
            ['name' => 'Aquaculture', 'college_id' => 6],
            ['name' => 'Aquatic Post Harvest', 'college_id' => 6],
            ['name' => 'Aquatic Resources, Ecology and Management', 'college_id' => 6],

            // College of Home Science and Industry (college_id: 7)
            ['name' => 'Food Science and Technology', 'college_id' => 7],
            ['name' => 'Hospitality and Tourism', 'college_id' => 7],
            ['name' => 'Textile and Garment Technology', 'college_id' => 7],

            // College of Science (college_id: 8)
            ['name' => 'Biological Sciences', 'college_id' => 8],
            ['name' => 'Chemistry', 'college_id' => 8],
            ['name' => 'Environmental Science', 'college_id' => 8],
            ['name' => 'Mathematics and Physics', 'college_id' => 8],
            ['name' => 'Statistics', 'college_id' => 8],

            // College of Veterinary Science and Medicine (college_id: 9)
            ['name' => 'Animal Management', 'college_id' => 9],
            ['name' => 'Morphophysiology and Pharmacology', 'college_id' => 9],
            ['name' => 'Pathobiology', 'college_id' => 9],
            ['name' => 'Veterinary and Clinical Sciences', 'college_id' => 9],

            // Distance, Open and Transnational University (college_id: 10)
            ['name' => 'Distance, Open and Transnational University', 'college_id' => 10],

            // Office of Admissions (college_id: 11) — no departments in SQL

            // NSTP (college_id: 12)
            ['name' => 'Civic Welfare Training Service', 'college_id' => 12],
            ['name' => 'Reserve Officers Training Corps', 'college_id' => 12],
        ];

        Department::insert($departments);
    }
}