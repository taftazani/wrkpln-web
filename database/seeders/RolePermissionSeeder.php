<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'Owner']);
        $employeeRole = Role::create(['name' => 'Employee']);
        $managerRole = Role::create(['name' => 'Manager']);

        // Create permissions
        $permissions = [
            'view_dashboard',
            'manage_tempat_kerja',
            'manage_shift',
            'manage_jadwal',
            'manage_izin',
            'manage_absensi',
            'manage_lembur',
            'manage_kpi',
            'view_report',
            'manage_payroll',
            'manage_advance',
            'manage_page_settings',
            'manage_user_management',
            'manage_role_management',
            'todo_list',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->permissions()->sync(Permission::all());
        $managerRole->permissions()->sync(
            Permission::whereIn('name', [
                'view_dashboard',
                'manage_tempat_kerja',
                'manage_shift',
                'manage_jadwal',
                'manage_izin',
                'manage_absensi',
                'manage_lembur',
                'manage_kpi',
                'view_report',
                'manage_payroll',
                'manage_advance',
                'todo_list',
            ])->get()
        );
        $employeeRole->permissions()->sync(
            Permission::whereIn('name', [
                'view_dashboard',
            ])->get()
        );
    }
}
