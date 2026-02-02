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
            'role-permission',
        ];

        foreach ($crudModules as $module) {
            foreach (['view', 'create', 'edit', 'delete'] as $action) {
                Permission::firstOrCreate(['name' => "{$module}.{$action}"]);
            }
        }

        Permission::firstOrCreate(['name' => 'user.view']);
        Permission::firstOrCreate(['name' => 'user.create']);
        Permission::firstOrCreate(['name' => 'user.edit']);

        Permission::firstOrCreate(['name' => 'user.archive.view']);
        Permission::firstOrCreate(['name' => 'user.archive.restore']);
        Permission::firstOrCreate(['name' => 'user.archive.delete']);

        Permission::firstOrCreate(['name' => 'position.view']);
        Permission::firstOrCreate(['name' => 'position.create']);
        Permission::firstOrCreate(['name' => 'position.edit']);

        Permission::firstOrCreate(['name' => 'applicant.view']);
        Permission::firstOrCreate(['name' => 'applicant.edit']);
        Permission::firstOrCreate(['name' => 'applicant.scheduled']);

        Permission::firstOrCreate(['name' => 'screening.view']);
        Permission::firstOrCreate(['name' => 'screening.export']);
        Permission::firstOrCreate(['name' => 'nbc.view']);
        Permission::firstOrCreate(['name' => 'nbc.export']);

        Permission::firstOrCreate(['name' => 'notification']);
        Permission::firstOrCreate(['name' => 'message']);
        Permission::firstOrCreate(['name' => 'assign-position.view']);

        Permission::firstOrCreate(['name' => 'assign.position.archive.view']);
        Permission::firstOrCreate(['name' => 'assign.position.archive.restore']);
        Permission::firstOrCreate(['name' => 'assign.position.archive.delete']);

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole  = Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'applicant']);
        Role::firstOrCreate(['name' => 'panel']);
        Role::firstOrCreate(['name' => 'nbc']);

        $superAdmin->syncPermissions(Permission::all());

        $restrictedModules = [
            'position-rank',
            'college',
            'department',
            'user',
            'role-permission',
            'assign.position.archive',
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