<?php

namespace Database\Factories;

use App\Models\StudentBatch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentBatch>
 */
class StudentBatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = StudentBatch::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'academic_year' => $this->faker->year,
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
