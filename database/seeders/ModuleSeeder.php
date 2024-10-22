<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::create(['course_id' => 1, 'code' => 'CS101', 'name' => 'Introduction to Computer Science', 'description' => 'Basic concepts of computer science.', 'status' => 'draft']);
        Module::create(['course_id' => 2, 'code' => 'BA101', 'name' => 'Introduction to Business', 'description' => 'Overview of business principles.', 'status' => 'publish']);
    }
}
