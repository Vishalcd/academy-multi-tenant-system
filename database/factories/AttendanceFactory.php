<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::inRandomOrder()->first()?->id ?? Student::factory(),
            'recorded_by' => User::where('role', 'employee')->inRandomOrder()->first()?->id ?? User::factory(),
            'date' => now(),
            'status' => fake()->randomElement(['present', 'absent']),
        ];
    }
}
