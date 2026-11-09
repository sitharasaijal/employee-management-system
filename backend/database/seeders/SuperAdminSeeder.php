<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $department = Department::firstOrCreate(
            ['code' => 'ADM'],
            ['name' => 'Administration']
        );

        $designation = $department->designations()->firstOrCreate(
            ['name' => 'System Administrator']
        );

        $admin = Employee::firstOrNew(['email' => 'admin@ems.test']);

        $admin->forceFill([
            'employee_code'   => 'EMP-00001',
            'first_name'      => 'System',
            'last_name'       => 'Admin',
            'mobile'          => '0500000000',
            'gender'          => 'male',
            'joining_date'    => now()->toDateString(),
            'department_id'   => $department->id,
            'designation_id'  => $designation->id,
            'employment_type' => 'full_time',
            'status'          => 'active',
            'password'        => env('ADMIN_PASSWORD', 'ChangeMe@123'),
            'login_enabled'   => true,
        ])->save();

        $admin->syncRoles(['Super Admin']);
    }
}