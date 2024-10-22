<?php

namespace Database\Factories;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Module>
 */
class ModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Module::class;

    public function definition()
    {
        return [
            'course_id' => \App\Models\Course::factory(), // Assuming CourseFactory exists
            'code' => $this->faker->unique()->word,
            'name' => $this->faker->sentence(3),
            'semester' => $this->faker->numberBetween(1, 8),
            'description' => $this->faker->paragraph,
            'credit' => $this->faker->numberBetween(1, 5),
            'status' => $this->faker->randomElement(['draft', 'publish']),
        ];
    }
}
