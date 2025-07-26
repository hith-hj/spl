<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PartnersTypes;
use App\Models\Partner;
use App\Models\Stadium;
use App\Models\Trainer;
use App\Models\User;

final class PartnerServices
{
    public function get(int $id): Partner
    {
        return $this->find($id);
    }

    public function find(int $id): Partner
    {
        Required($id, 'partner id');
        $partner = Partner::find($id);
        NotFound($partner, 'partner');

        return $partner;
    }

    public function update(User|Partner $partner, array $data): bool
    {
        Required($data, 'partner data');

        return $partner->update($data);
    }

    public function delete(int $id): bool
    {
        $partner = Partner::find($id);
        NotFound($partner, 'partner');
        (new PartnerAuthServices())->logout();
        $this->clear($partner);

        return $partner->delete();
    }

    public function createStadium(Partner $partner, array $data): Stadium
    {
        Required($data, 'stadium data');
        Truthy($partner->type !== PartnersTypes::stadium->name, 'not stadium, invalid operation');

        return $partner->details()->create($data);
    }

    public function createTrainer(Partner $partner, array $data): Trainer
    {
        Required($data, 'trainer data');
        Truthy($partner->type !== PartnersTypes::trainer->name, 'not trainer, invalid operation');

        return $partner->details()->create($data);
    }

    public function updateStadium(Partner $partner, array $data): int
    {
        Required($data, 'stadium data');
        Truthy($partner->type !== PartnersTypes::stadium->name, 'not stadium, invalid operation');

        return $partner->details()->update($data);
    }

    public function updateTrainer(Partner $partner, array $data): int
    {
        Required($data, 'trainer data');
        Truthy($partner->type !== PartnersTypes::trainer->name, 'not trainer, invalid operation');

        return $partner->details()->update($data);
    }

    private function clear(Partner|int $partner): void
    {
        if ($partner instanceof int) {
            $partner = $this->find($partner);
        }
        $partner->details()->delete();
        $partner->badge->workdays()?->delete();
        $partner->badge->activities()?->delete();
        $partner->badge->medias()?->delete();
        // $partner->badge->appointments()?->delete();
    }
}
