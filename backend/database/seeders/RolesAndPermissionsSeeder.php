<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'employees.view', 'employees.create', 'employees.edit',
            'employees.change_status', 'employees.view_history',
            'departments.view', 'departments.manage',
            'designations.view', 'designations.manage',
            'reports.view', 'reports.export',
            'departments.access_all',
            'users.manage', 'settings.manage',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

        Role::firstOrCreate(['name' => 'Super Admin'])
            ->syncPermissions(Permission::all());

        Role::firstOrCreate(['name' => 'HR Manager'])
            ->syncPermissions(array_diff($permissions, ['users.manage', 'settings.manage']));

        Role::firstOrCreate(['name' => 'HR Staff'])
            ->syncPermissions([
                'employees.view', 'employees.create', 'employees.edit',
                'employees.view_history', 'departments.view',
                'designations.view', 'reports.view',
            ]);
    }
}