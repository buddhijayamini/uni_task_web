<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $academicHeadRole = Role::firstOrCreate(['name' => 'Academic Head']);
        $teacherRole = Role::firstOrCreate(['name' => 'Teacher']);
        $studentRole = Role::firstOrCreate(['name' => 'Student']);

        //permission create and assign to roles
        Permission::create(['name' => 'manage courses'])->assignRole([$academicHeadRole, $adminRole]);
        Permission::create(['name' => 'access course'])->assignRole([$teacherRole, $studentRole, $adminRole, $academicHeadRole]);

        Permission::create(['name' => 'manage modules'])->assignRole([$academicHeadRole, $adminRole]);
        Permission::create(['name' => 'access module'])->assignRole([$teacherRole, $studentRole, $adminRole, $academicHeadRole]);

        Permission::create(['name' => 'manage student batches'])->assignRole([$academicHeadRole, $adminRole]);

        Permission::create(['name' => 'manage course semesters'])->assignRole([$academicHeadRole, $adminRole]);
        Permission::create(['name' => 'access course semester'])->assignRole([$teacherRole, $studentRole, $adminRole, $academicHeadRole]);
        
        Permission::create(['name' => 'manage tutors'])->assignRole([$adminRole]);

    }
}
