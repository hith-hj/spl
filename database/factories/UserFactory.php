<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AccountStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
final class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'email' => fake()->unique()->email,
            'phone' => fake()->regexify("(09)[1-9]{1}\d{7}"),
            'password' => bcrypt('password'),
            'fb_token' => Str::random(64),
            'verified_by' => 'phone',
            'verified_at' => now(),
            'birth_day' => fake()->date,
            'gender' => fake()->boolean(),
            'is_active' => true,
            'status' => AccountStatus::fresh->value,
        ];
    }
}
