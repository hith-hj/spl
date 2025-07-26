<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Partner;
use App\Models\Workday;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

final class WorkdayServices
{
    public function allByPartner(Partner $partner): Collection|Model
    {
        Required($partner, 'partner');
        $workdays = $partner->badge->workdays;
        NotFound($workdays, 'Workdays');

        return $workdays;
    }

    public function findByPartner(Partner $partner, int $id): Workday
    {
        Required($partner, 'parnter');
        $day = $partner->badge->workdays()->where('id', $id)->first();
        NotFound($day);

        return $day;
    }

    public function create(Partner $partner, array $data): Workday
    {
        Required($partner, 'parnter');
        Required($data, 'day data');
        $oldDays = $partner->badge->workdays->toArray();
        [$slot_duration_id, $data] = $this->getSlotDurationIdOutFromData($data);
        $data = $this->formatData($data);
        $this->checkIfAvailable($data, $oldDays);
        $day = $partner->badge->workdays()->create($data);
        $day->createSlots($day->from, $day->to, $slot_duration_id);

        return $day;
    }

    public function update(Partner $partner, Workday $day, array $data): Workday
    {
        Required($partner, 'parnter');
        Required($data, 'day data');
        $oldDays = $partner->badge->workdays->toArray();
        [$slot_duration_id, $data] = $this->getSlotDurationIdOutFromData($data);
        $data = $this->formatData($data, $day);
        $this->checkIfAvailable($data, $oldDays, true);
        $day->slots()->delete();
        $day->update($data);
        $day->createSlots($day->from, $day->to, $slot_duration_id);

        return $day;
    }

    public function delete(Workday $day): bool
    {
        $day->slots()->delete();

        return $day->delete();
    }

    public function toggleActivation(Workday $day): bool
    {
        return $day->update(['is_active' => ! $day->is_active]);
    }

    public function checkIfAvailable(array $newDay, array $oldDays, bool $updateing = false)
    {
        foreach ($oldDays as $od) {
            if ($od['day'] === $newDay['day']) {
                if ($newDay['from'] === $od['from'] && $newDay['to'] === $od['to']) {
                    throw new Exception("Duplicated Day {$od['day']} at {$od['from']}"); // dublication
                }
                if ($updateing && isset($newDay['id']) && $newDay['id'] === $od['id']) {
                    continue;
                }
                if ($newDay['from'] < $od['to'] && $newDay['to'] > $od['from']) {
                    throw new Exception("Conflict with {$od['day']} at {$od['from']}"); // intersecting
                }
            }
        }

        return true; // avaiable
    }

    private function getSlotDurationIdOutFromData(array $data)
    {
        Required($data['slot_duration_id'], 'slot duration id');
        $slot_duration_id = $data['slot_duration_id'];
        unset($data['slot_duration_id']);

        return [$slot_duration_id, $data];
    }

    private function formatData(array $data, ?Workday $day = null)
    {
        $data['day'] = isset($data['day']) ? mb_strtolower($data['day']) : '';
        $data['is_active'] = 0;
        if ($day !== null) {
            $data['id'] = $day->id;
            $data['day'] = mb_strtolower($day->day);
        }

        return $data;
    }
}
