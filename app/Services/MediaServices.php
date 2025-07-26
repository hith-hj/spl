<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Media;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

final class MediaServices
{
    public function allByPartner(Partner $partner): Collection|Model
    {
        Required($partner, 'partner');
        $medias = $partner->badge->medias;
        NotFound($medias, 'medias');

        return $medias;
    }

    public function findByPartner(Partner $partner, int $id): Media
    {
        Required($partner, 'parnter');
        $media = $partner->badge->medias()->where('id', $id)->first();
        NotFound($media, 'media');

        return $media;
    }

    public function findByPartnerByName(Partner $partner, string $name): Media
    {
        Required($partner, 'partner');
        Required($name, 'name');
        $media = $partner->badge->mediaByName($name);
        NotFound($media, 'media');

        return $media;
    }

    public function findByPartnerByGroup(Partner $partner, string $group): Collection
    {
        Required($partner, 'partner');
        Required($group, 'group');
        $media = $partner->badge->mediaByGroup($group);
        NotFound($media, 'media');

        return $media;
    }

    public function create(Partner $partner, array $data): Collection|Media
    {
        Required($partner, 'parnter');
        Required($data, 'media data');
        $data = $this->formatData($data);
        $media = $partner->badge->multible($data);

        return $media;
    }

    public function update(Partner $partner, Media $media, array $data): Media
    {
        Required($partner, 'parnter');
        Required($data, 'media data');
        if (isset($data['group'])) {
            $group = $partner->badge->mediaByGroup($data['group']);
            NotFound($group, 'group');
        }
        $data = $this->formatData($data);
        $media->update($data);

        return $media;
    }

    public function delete(Media $media): bool
    {
        return $media->delete();
    }

    public function deleteByGroup(Collection $media): int
    {
        $ids = $media->pluck('id')->toArray();

        return Media::whereIn('id', $ids)->delete();
    }

    public function formatData(array $data): array
    {
        return $data;
    }
}
