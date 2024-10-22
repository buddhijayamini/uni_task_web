<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create(['name' => 'Computer Science', 'seo_url' => 'computer-science', 'faculty_id' => 1, 'category_id' => 1, 'status' => 'draft']);
        Course::create(['name' => 'Business Administration', 'seo_url' => 'business-administration', 'faculty_id' => 2, 'category_id' => 2, 'status' => 'publish']);
    }
}
