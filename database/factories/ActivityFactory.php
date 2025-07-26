<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

final class ActivityFactory extends Factory
{
    public function definition(): array
    {
        $is_trial = fake()->boolean();
        $trial_option = null;
        $discount_amount = null;
        if ($is_trial) {
            $trial_option = fake()->randomElement(['free', 'discount']);
            $discount_amount = $trial_option === 'discount' ? mt_rand(1, 30) : 100;
        }
        $cost = mt_rand(100, 1500);

        $is_private = fake()->boolean();
        $private_cost = null;
        if ($is_private) {
            $private_cost = $cost * 2;
        }

        return [
            'name' => fake()->colorName,
            'description' => fake()->paragraph,
            'type' => fake()->randomElement(['vip', 'normal']),
            'is_trial' => $is_trial,
            'trial_option' => $trial_option,
            'discount_amount' => $discount_amount,
            'cost' => $cost,
            'cancellation_cost' => $cost / 2,
            'is_active' => 1,
            'is_private' => $is_private,
            'private_cost' => $private_cost,
        ];
    }
}
