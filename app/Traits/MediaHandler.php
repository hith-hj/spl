<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Media;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait MediaHandler
{
    public function medias(): HasMany
    {
        return $this->hasMany(Media::class, 'belongTo_id')
            ->withAttributes(['belongTo_type' => $this::class]);
    }

    public function mediaByGroup(?string $group = ''): Collection
    {
        return $this->medias()->where('group', $group)->get();
    }

    public function mediaByName(?string $name = ''): ?Model
    {
        return $this->medias()->where('name', $name)->first();
    }

    public function multible(array $data): Collection
    {
        $uploads = [];
        foreach ($data['media'] as $media) {
            $uploads[] = $this->uploadMedia(
                type: $data['type'],
                group: $data['group'] ?? null,
                name: $media['name'] ?? null,
                file: $media['file'],
            );
        }

        return Collection::make($uploads);
    }

    public function uploadMedia(
        string $type,
        ?string $group,
        ?string $name,
        UploadedFile $file,
    ): Media {
        $type = $this->getFileType($file);
        $fileName = time().'_'.$file->hashName();
        $path = $file->storeAs(
            $this->getFolder($type),
            $fileName,
            'public'
        );

        return $this->medias()->create([
            'media' => $path,
            'type' => $type,
            'name' => $this->getFileName($file, $name),
            'group' => Str::lower($group),
        ]);
    }

    private function getFileType(UploadedFile $file): string
    {
        $mime = $this->getAllowedMime($file);

        return str_starts_with($mime, 'video') ? 'video' : 'image';
    }

    private function getFileName(UploadedFile $file, ?string $name = null): string
    {
        if ($name !== null) {
            return $name;
        }

        return explode('.', $file->getClientOriginalName())[0];
    }

    private function getAllowedMime(UploadedFile $file): string
    {
        $mime = $file->getMimeType();
        $allowed = ['image/jpeg', 'image/jpg', 'image/png', 'video/mp4'];

        if (! in_array($mime, $allowed)) {
            throw new Exception("File type $mime is not supported");
        }

        return $mime;
    }

    private function getFolder(string $type): string
    {
        return sprintf(
            'uploads/%s/%s/%s',
            Str::plural($type),
            Str::plural(class_basename($this::class)),
            $this->id
        );
    }
}
