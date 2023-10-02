<?php

namespace Database\Factories;

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
            'name' => $this->faker->name(),
            'nik' => $this->faker->nik(),
            'email' => $this->faker->unique()->safeEmail(),
            'gender' => $this->faker->randomElement(['MALE', 'FEMALE']),
            'age' => $this->faker->numberBetween(17, 55),
            'address' => $this->faker->address(),
            'education' => $this->faker->sentence(),
            'phone' => $this->faker->phoneNumber(),
            'date_entry' => $this->faker->date(),
            'year_service' => $this->faker->date('Y_m_d'),
            'position' => $this->faker->jobTitle(),
            'photo' => $this->faker->imageUrl(),

            'team_id' => $this->faker->numberBetween(1, 30),
            'violation_id' => $this->faker->numberBetween(1, 50),
        ];
    }
}
