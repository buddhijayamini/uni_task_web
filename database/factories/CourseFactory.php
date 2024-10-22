<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'seo_url' => $this->faker->slug(),
            'faculty' => $this->faker->word(),
            'category' => $this->faker->word(),
            'status' => $this->faker->randomElement(['draft', 'publish']),
        ];
    }
}
