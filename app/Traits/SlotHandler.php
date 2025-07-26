<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Slot;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

trait SlotHandler
{
    public function slots(): HasMany
    {
        return $this->hasMany(Slot::class, 'belongTo_id')
            ->withAttributes(['belongTo_type' => $this::class]);
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class, 'belongTo_id')
            ->withAttributes(['belongTo_type' => $this::class]);
    }

    public function createSlots(string $from, string $to, int $slotDuration_id): Collection
    {
        $slots = $this->generateSlots($from, $to, $slotDuration_id);

        return $this->slots()->createMany($slots);
    }

    public function getSlotsDuration(?int $id = null): array
    {
        Truthy($id === null, 'missing slot duration id');

        return (array) DB::table('slots_durations')->where('id', $id)->first(['unit', 'value']);
    }

    private function generateSlots($from, $to, $slotDuration_id): array
    {
        $interval = $this->getSlotsDuration($slotDuration_id);
        $slotLength = CarbonInterval::make($interval['value'], $interval['unit']);
        $skip = $this->getSkipAmount();
        $gap = CarbonInterval::make($skip['value'], $skip['unit']);

        $start = Carbon::today()->setHour((int) $from)->setMinutes(0);
        $end = Carbon::today()->setHour((int) $to);

        $slots = [];

        while ($start->copy()->add($slotLength)->lte($end)) {
            $slotStart = $start->copy();
            $slotEnd = $slotStart->copy()->add($slotLength);

            $slots[] = [
                'duration_id' => $slotDuration_id,
                'start' => $slotStart->format('H:i'),
                'end' => $slotEnd->format('H:i'),
            ];
            // Move to the next slot start = end of current + gap
            $start = $slotEnd->copy()->add($gap);
        }

        return $slots;
    }

    private function getSkipAmount(): array
    {
        if (config('settings.slots.skipTime', false)) {
            return [
                'unit' => config('settings.slots.skipTime_unit', 'minute'),
                'value' => config('settings.slots.skipTime_value', 15),
            ];
        }

        return ['unit' => 'minute', 'value' => 0];
    }
}
