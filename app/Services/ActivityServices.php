<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Activity;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

final class ActivityServices
{
    public function allByPartner(Partner $partner): Collection|Model
    {
        Required($partner, 'partner');
        $activities = $partner->badge->activities;
        NotFound($activities, 'activities');

        return $activities;
    }

    public function findByPartner(Partner $partner, int $id): Activity
    {
        Required($partner, 'parnter');
        $activity = $partner->badge->activities()->where('id', $id)->first();
        NotFound($activity, 'activity');

        return $activity;
    }

    public function create(Partner $partner, array $data): Activity
    {
        Required($partner, 'parnter');
        Required($data, 'activity data');
        $data = $this->formatData($data);
        $activity = $partner->badge->activities()->create($data);

        return $activity;
    }

    public function update(Partner $partner, Activity $activity, array $data): Activity
    {
        Required($partner, 'parnter');
        Required($data, 'activity data');
        $data = $this->formatData($data);
        $activity->update($data);

        return $activity;
    }

    public function delete(Activity $activity): bool
    {
        return $activity->delete();
    }

    public function toggleActivation(Activity $activity): bool
    {
        return $activity->update(['is_active' => ! $activity->is_active]);
    }

    private function formatData(array $data, ?Activity $activity = null): array
    {
        $data['is_active'] = 0;

        return $data;
    }
}
