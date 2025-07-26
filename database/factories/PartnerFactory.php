<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AccountStatus;
use App\Enums\PartnersTypes;
use App\Models\Category;
use App\Models\Course;
use App\Models\Court;
use App\Models\Partner;
use App\Models\Stadium;
use App\Models\Trainer;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class PartnerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->email,
            'username' => fake()->userName,
            'phone' => fake()->unique()->regexify("(09)[1-9]{1}\d{7}"),
            'password' => bcrypt('password'),
            'fb_token' => Str::random(64),
            'type' => fake()->randomElement(['stadium', 'trainer']),
            'status' => AccountStatus::fresh->value,
            'is_active' => true,
            'is_visible' => true,
            'verified_by' => 'phone',
            'verified_at' => now(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Partner $partner) {
            Category::factory()->hasAttached($partner, relationship: 'partners')->create();

            return match ($partner->type) {
                PartnersTypes::stadium->name => $this->stadium($partner),
                PartnersTypes::trainer->name => $this->trainer($partner),
                default => throw new Exception("invalid type $partner->type"),
            };
        });
    }

    private function stadium($partner)
    {
        Stadium::factory()->for($partner, 'partner')->create();

        return $this->courts($partner);
    }

    private function courts($partner)
    {
        Court::factory()->for($partner, 'partner')->create(['is_main' => true]);
        Court::factory()->for($partner, 'partner')->create();
    }

    private function trainer($partner)
    {
        Trainer::factory()->for($partner, 'partner')->create();

        return $this->courses($partner);
    }

    private function courses($partner)
    {
        $court = Court::find(1);
        Course::factory()
            ->for($partner, 'partner')
            ->for($court, 'court')
            ->create(['is_main' => true]);
        Course::factory()
            ->for($partner, 'partner')
            ->create();
    }
}
