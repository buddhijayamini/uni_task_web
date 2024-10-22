<?php

namespace Database\Seeders;

use App\Models\StudentBatch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $batches = [
        ['name' => 'Batch 2023', 'academic_year' => '2023-2024', 'status' => 'active'],
        ['name' => 'Batch 2024', 'academic_year' => '2024-2025', 'status' => 'active'],
        ];

        foreach ($batches as $batch) {
            StudentBatch::create($batch);
        }
    }
}
