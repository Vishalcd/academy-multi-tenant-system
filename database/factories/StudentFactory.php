<?php

namespace Database\Factories;

use App\Models\Sport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\student>
 */
class studentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sport_id' => fake()->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9]),
            'user_id' => User::factory(),
            'total_fees' => fake()->randomElement([30000, 50000]),
            'fees_due' => fake()->randomElement([0, 20000, 5000, 30000]),
            'fees_settle' => fake()->boolean(),
            'batch' => fake()->randomElement(['a', 'b', 'c']),
        ];
    }
}
