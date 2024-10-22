<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $semesters = [
            ['name' => 'SEM: 01', 'elective_credits_limit' => 2],
            ['name' => 'SEM: 02', 'elective_credits_limit' => 2],
            ['name' => 'SEM: 03', 'elective_credits_limit' => 2],
            ['name' => 'SEM: 04', 'elective_credits_limit' => 2],
        ];

        foreach ($semesters as $semester) {
            Semester::create($semester);
        }

    }
}
