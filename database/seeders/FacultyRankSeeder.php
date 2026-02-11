<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FacultyRankSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $ranks = [
            // Instructor
            ['name' => 'Instructor I', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Instructor II', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Instructor III', 'created_at' => $now, 'updated_at' => $now],

            // Assistant Professor
            ['name' => 'Assistant Professor I', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Assistant Professor II', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Assistant Professor III', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Assistant Professor IV', 'created_at' => $now, 'updated_at' => $now],

            // Associate Professor
            ['name' => 'Associate Professor I', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Associate Professor II', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Associate Professor III', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Associate Professor IV', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Associate Professor V', 'created_at' => $now, 'updated_at' => $now],

            // Professor
            ['name' => 'Professor I', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Professor II', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Professor III', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Professor IV', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Professor V', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Professor VI', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('position_ranks')->insert($ranks);
    }
}
