<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Create Admin User
         $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'), // Use a secure password
        ]);
        $admin->assignRole('Admin');

        // Create Academic Head User
        $academicHead = User::create([
            'name' => 'Academic Head User',
            'email' => 'academic_head@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
        $academicHead->assignRole('Academic Head');

        // Create Teacher User
        $teacher = User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
        $teacher->assignRole('Teacher');

        // Create Student User
        $student = User::create([
            'name' => 'Student User',
            'email' => 'student@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
        $student->assignRole('Student');
    }

}
