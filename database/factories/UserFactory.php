<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [ //IF DON'T WORK PLEASE CHEK HERE 
            'name' => fake()->firstNameFemale(),
            'last_name' => fake()->lastName(),
            'father_name' => fake()->userName(),
            'dob' => fake()->date('Y'),
            'nic' => fake()->unique()->numberBetween(100, 10000),
            'hire_date' => now(),
            'gross_salary' => fake()->numberBetween(1000, 100000),
            'phone' => fake()->unique()->phoneNumber(),
            'photo' => 'image',
            'account_status' => 1,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('admin123'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
