<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class ApplicantSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $password = '12345678';

        for ($i = 1; $i <= 21; $i++) {

            // 1️⃣ Create user (NO role column)
            $userId = DB::table('users')->insertGetId([
                'name'       => $faker->firstName . ' ' . $faker->lastName,
                'email'      => "applicant{$i}@example.com",
                'password'   => $password,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2️⃣ Assign role via model_has_roles (Spatie)
            DB::table('model_has_roles')->insert([
                'role_id'    => 3, // Applicant
                'model_type' => 'App\Models\User',
                'model_id'   => $userId,
            ]);

            // 3️⃣ Create applicant profile
            DB::table('applicants')->insert([
                'first_name'   => $faker->firstName,
                'middle_name'  => strtoupper($faker->randomLetter),
                'last_name'    => $faker->lastName,
                'suffix'       => null,
                'phone_number' => null,
                'region'       => null,
                'province'     => null,
                'city'         => null,
                'barangay'     => null,
                'street'       => null,
                'postal_code'  => null,
                'position'     => null,
                'hired'        => 0,
                'user_id'      => $userId,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
