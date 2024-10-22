<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faculties = [
            ['name' => 'Faculty of Science'],
            ['name' => 'Faculty of Arts'],
            ['name' => 'Faculty of Engineering'],
            ['name' => 'Faculty of Business'],
        ];

        foreach ($faculties as $faculty) {
            Faculty::create($faculty);
        }

    }
}
