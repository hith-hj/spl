<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PartnersTypes;
use App\Models\Court;
use App\Models\Partner;
use Exception;
use Illuminate\Support\Collection;

final class CourtServices
{
    private $itemsCount = 25;

    public function allByPartner(Partner $partner): Collection
    {
        Required($partner, 'partner');
        $courts = $partner->courts()->get(['id', 'name', 'is_main', 'is_active']);
        NotFound($courts, 'courts');

        return $courts;
    }

    public function mainByPartner(Partner $partner, ?int $count = null): Court
    {
        Required($partner, 'partner');
        $court = $partner->badge;
        NotFound($court, 'court');

        return $court->load($this->loadableRelation($count));
    }

    public function findByPartner(Partner $partner, int $id): Court
    {
        Required($partner, 'parnter');
        $court = $partner->courts()->where('id', $id)->first();
        NotFound($court, 'court');

        return $court;
    }

    public function create(Partner $partner, array $data): Court
    {
        Required($data, 'court data');
        Truthy($partner->type !== PartnersTypes::stadium->name, 'not stadium');
        $court = $partner->courts()->create($data);
        if ($partner->courts()->count() === 1) {
            $this->setMain($partner, $court);
        }

        return $court;
    }

    public function update(Court $court, array $data): Court
    {
        Required($data, 'court data');
        $court->update($data);

        return $court;
    }

    public function delete(Court $court): bool
    {
        if ($court->is_main) {
            throw new Exception('court is main');
        }
        if (method_exists($court, 'deleteRelations')) {
            $court->deleteRelations();
        }

        return $court->delete();
    }

    public function setMain(Partner $partner, Court $court): Court
    {
        if ($court->is_main) {
            throw new Exception('court is main');
        }
        $main = $partner->courts()->where('is_main', true);
        if ($main->exists()) {
            $main->update(['is_main' => false]);
        }
        $court->update(['is_main' => true]);

        return $court;
    }

    public function toggleActivation(Court $court): bool
    {
        return $court->update(['is_active' => ! $court->is_active]);
    }

    private function loadableRelation(?int $count = null)
    {
        if (! $count === null) {
            $this->itemsCount = $count;
        }

        return [
            'workdays' => fn ($query) => $query->limit($this->itemsCount),
            'activities' => fn ($query) => $query->limit($this->itemsCount),
            'medias' => fn ($query) => $query->limit($this->itemsCount),
        ];
    }
}
