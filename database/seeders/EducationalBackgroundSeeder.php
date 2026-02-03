<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EducationalBackground;

class EducationalBackgroundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run()
    {
        $degrees = [
            "Bachelor's Degree",
            "Master's Degree",
            "Doctorate Degree",

            "Master of Science in Accountancy",
            "Master of Science in Agricultural Engineering",
            "Master of Science in Agricultural and Biosystems Engineering",
            "Master of Science in Agricultural Economics",
            "Master of Science in Agricultural Extension",
            "Master of Science in Animal Science",
            "Master of Science in Biology",
            "Master of Science in Chemistry",
            "Master of Science in Civil Engineering",
            "Master of Science in Computer Science",
            "Master of Science in Crop Protection",
            "Master of Science in Crop Science",
            "Master of Science in Electrical Engineering",
            "Master of Science in Mechanical Engineering",
            "Master of Science in Environmental Science",
            "Master of Science in Food Science",
            "Master of Science in Information Technology",
            "Master of Science in Mathematics",
            "Master of Science in Physics",
            "Master of Science in Soil Science",
            "Master of Science in Statistics",
            "Master of Science in Tourism Management",
            "Master of Science in Veterinary Medicine",
            "Master of Science in Veterinary Science",

            "Master of Arts in Education",
            "Master of Arts in Education (Major in Guidance and Counseling)",
            "Master of Arts in Education (Major in Social Studies)",
            "Master of Arts in English and Literature",
            "Master of Arts in Early Childhood Education",
            "Master of Arts in Physical Education",
            "Master of Arts in Technology and Livelihood Education",
            "Master of Arts in Values Education",
            "Master of Arts in Development Communication",
            "Master of Arts in International Studies",
            "Master of Arts in Social Science",

            "Master of Business Administration",
            "Master in Public Administration",
            "Master in Management",
        ];

        foreach ($degrees as $degree) {
            EducationalBackground::firstOrCreate(['name' => $degree]);
        }
    }
}
