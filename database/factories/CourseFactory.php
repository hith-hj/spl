<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Course;
use App\Models\Location;
use App\Models\Workday;
use Illuminate\Database\Eloquent\Factories\Factory;

final class CourseFactory extends Factory
{
    public function definition(): array
    {
        $is_multiPerson = fake()->boolean;
        $capacity = $is_multiPerson ? mt_rand(1, 25) : null;
        $cost = mt_rand(100, 1000);
        $in_public = fake()->boolean;
        $court = $in_public ? null : 1;
        $type = fake()->randomElement(['daily', 'monthly']);
        $month_sessions = $type === 'monthly' ? mt_rand(1,30) : 1;
        return [
            'court_id' => $court,
            'name' => fake()->name,
            'type' => $type,  // daily,monthly
            'month_sessions' => $month_sessions,
            'description' => fake()->paragraph(1),
            'is_multiPerson' => $is_multiPerson,
            'capacity' => $capacity,
            'cost' => $cost,
            'cancellation_cost' => $cost / 2,
            'is_main' => fake()->boolean,
            'is_outdoor' => fake()->boolean,
            'is_active' => fake()->boolean,
            'in_public' => $in_public,
            'rate' => 0,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Course $cource) {
            $rand = mt_rand(1, 2);
            Workday::factory($rand)->for($cource, 'belongTo')->create();
            Activity::factory($rand)->for($cource, 'belongTo')->create();
            if($cource->is_public){
                Location::factory()->for($cource,'belongTo')->create();
            }
        });
    }
}
