<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trainer>
 */
final class TrainerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'birth_date' => fake()->date,
            'gender' => fake()->randomElement(['male', 'female']),
            'type' => fake()->randomElement(['sport_trainer', 'music_trainer ', 'yoga_trainer']),
            'description' => fake()->paragraph,
        ];
    }
}
