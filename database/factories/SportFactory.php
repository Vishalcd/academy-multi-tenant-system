<?php

namespace Database\Factories;

use App\Models\Academy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sport>
 */
class SportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'academy_id' => fake()->randomElement([1, 2, 3]),
            'sport_title' => fake()->sentence(1),
            'sport_fees' => fake()->randomElement([1000, 4000, 7000, 2500, 10000]),
            'photo' => fake()->imageUrl(100, 100, 'sports', true, 'photos'),
        ];
    }
}
