<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Court;
use App\Models\Media;
use App\Models\WorkDay;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Court>
 */
final class CourtFactory extends Factory
{
    public function definition(): array
    {
        return [
            'is_outdoor' => fake()->boolean(),
            'name' => fake()->name(),
            'type' => fake()->randomElement(['football', 'basketball', 'swimming']),
            'phone' => fake()->unique()->regexify("(09)[1-9]{1}\d{7}"),
            'description' => fake()->paragraph(1),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Court $court) {
            $rand = mt_rand(1, 2);
            WorkDay::factory($rand)->for($court, 'belongTo')->create();
            Activity::factory($rand)->for($court, 'belongTo')->create();
            Media::factory($rand)->for($court, 'belongTo')->create();
        });
    }
}
