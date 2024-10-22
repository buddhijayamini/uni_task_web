<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Computer Science', 'faculty_id' => 1],
            ['name' => 'Mathematics', 'faculty_id' => 1],
            ['name' => 'English', 'faculty_id' => 2],
            ['name' => 'Mechanical Engineering', 'faculty_id' => 3],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
