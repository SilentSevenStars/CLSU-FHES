<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------------------------------------------
        // PERMISSIONS
        // -------------------------------------------------------
        $crudModules = [
            'panel',
            'nbc-committee',
            'position-rank',
            'college',
            'department',
            'representative',
            'user',
            'role-permission',
        ];

        foreach ($crudModules as $module) {
            foreach (['view', 'create', 'edit', 'delete'] as $action) {
                Permission::firstOrCreate(['name' => "{$module}.{$action}"]);
            }
        }

        // Position
        Permission::firstOrCreate(['name' => 'position.view']);
        Permission::firstOrCreate(['name' => 'position.create']);
        Permission::firstOrCreate(['name' => 'position.edit']);

        // Applicant
        Permission::firstOrCreate(['name' => 'applicant.view']);
        Permission::firstOrCreate(['name' => 'applicant.edit']);
        Permission::firstOrCreate(['name' => 'applicant.scheduled']);

        // Screening & NBC
        Permission::firstOrCreate(['name' => 'screening.view']);
        Permission::firstOrCreate(['name' => 'screening.export']);
        Permission::firstOrCreate(['name' => 'nbc.view']);
        Permission::firstOrCreate(['name' => 'nbc.export']);

        // Standalone
        Permission::firstOrCreate(['name' => 'notification']);
        Permission::firstOrCreate(['name' => 'message']);
        Permission::firstOrCreate(['name' => 'assign-position.view']);

        // -------------------------------------------------------
        // ROLES
        // -------------------------------------------------------
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole  = Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'applicant']);
        Role::firstOrCreate(['name' => 'panel']);
        Role::firstOrCreate(['name' => 'nbc']);

        // Super Admin → ALL permissions
        $superAdmin->syncPermissions(Permission::all());

        // -------------------------------------------------------
        // ADMIN PERMISSIONS (EXCEPT SPECIFIC CRUD MODULES)
        // -------------------------------------------------------
        $restrictedModules = [
            'position-rank',
            'college',
            'department',
            'user',
            'role-permission',
        ];

        $excludedPermissions = Permission::where(function ($query) use ($restrictedModules) {
            foreach ($restrictedModules as $module) {
                $query->orWhere('name', 'like', "{$module}.%");
            }
        })->pluck('id');

        $adminPermissions = Permission::whereNotIn('id', $excludedPermissions)->get();

        $adminRole->syncPermissions($adminPermissions);

        // user account seed
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => '12345678',
                'email_verified_at' => Carbon::now(),
            ]
        );

        $admin->syncRoles(['super-admin']);
    }
}
