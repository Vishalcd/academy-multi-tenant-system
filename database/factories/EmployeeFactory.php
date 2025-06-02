<?php

namespace Database\Factories;

use App\Models\Sport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state([
                'role' => 'employee',
            ]),
            'sport_id' => fake()->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9]),
            'job_title' => fake()->randomElement(['coach']),
            'salary' => fake()->randomElement([10000, 30000, 50000]),
            'pending_salary' => fake()->randomElement([10000, 20000]),
            'last_paid' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
            'salary_settled' => fake()->boolean(),
        ];
    }
}
