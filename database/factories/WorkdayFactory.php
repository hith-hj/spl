<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Workday;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workday>
 */
final class WorkdayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'day' => fake()->dayOfWeek,
            'from' => (string) mt_rand(8, 14),
            'to' => (string) mt_rand(18, 21),
            'is_active' => 1,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Workday $day) {
            $slot_duration = (array) DB::table('slots_durations')->find(rand(1, 4));
            $duration = $slot_duration['value'].' '.$slot_duration['unit'];
            $from = Carbon::today()->setHour(+$day['from']);
            $to = Carbon::today()->setHour(+$day['to']);
            $periods = CarbonPeriod::create($from, $duration, $to);
            $res = collect($periods)->map(function ($dt) use ($duration) {
                return [
                    'start' => $dt->format('H:i'),
                    'end' => $dt->add($duration)->format('H:i'),
                ];
            });
            $data = [];
            foreach ($res as $slot) {
                $data[] = array_merge($slot, ['duration_id' => $slot_duration['id']]);
            }
            $day->slots()->createMany($data);

            return true;
        });
    }
}
