<?php

namespace Database\Seeders;

use App\Models\College;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollegeSeeder extends Seeder
{
    public function run(): void
    {
        $colleges = [
            ['name' => 'College of Agriculture'],
            ['name' => 'College of Arts and Social Sciences'],
            ['name' => 'College of Business Administration and Accountancy'],
            ['name' => 'College of Education'],
            ['name' => 'College of Engineering'],
            ['name' => 'College of Fisheries'],
            ['name' => 'College of Home Science and Industry'],
            ['name' => 'College of Science'],
            ['name' => 'College of Veterinary Science and Medicine'],
            ['name' => 'Distance, Open and Transnational University'],
            ['name' => 'Office of Admissions'],
            ['name' => 'NSTP'],
        ];

        College::insert($colleges);
    }
}