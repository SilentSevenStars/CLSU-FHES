<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => '12345678',
        ]);
        User::factory()->create([
            'name' => 'Applicant User',
            'email' => 'applicant@example.com',
            'role' => 'applicant',
            'password' => '12345678',
        ]);
        User::factory()->create([
            'name' => 'Panel User',
            'email' => 'panel@example.com',
            'role' => 'panel',
            'password' => '12345678',
        ]);
        $this->call([
            CollegeSeeder::class,
            DepartmentSeeder::class,
        ]);
    }
}
