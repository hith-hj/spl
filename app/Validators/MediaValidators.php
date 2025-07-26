<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

final class MediaValidators
{
    public static function find(array $data)
    {
        return Validator::make($data, [
            'media_id' => ['required', 'exists:media,id'],
        ]);
    }

    public static function findByName(array $data)
    {
        return Validator::make($data, [
            'media_name' => ['required', 'exists:media,name'],
        ]);
    }

    public static function findByGroup(array $data)
    {
        return Validator::make($data, [
            'media_group' => ['required', 'exists:media,group'],
        ]);
    }

    public static function create(array $data)
    {
        $validator = Validator::make($data, [
            'group' => ['nullable', 'string', 'max:25'],
            'type' => ['required', 'in:image,video'],
            'media' => ['required', 'array', 'min:1', 'max:5'],
            'media.*' => ['required', 'array:file,name'],
            'media.*.name' => ['nullable', 'string', 'max:20'],
        ]);

        $validator->sometimes(
            'media.*.file',
            ['file', 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4', 'max:20480'],
            fn ($input) => $input->type === 'video'
        );

        $validator->sometimes(
            'media.*.file',
            ['image', 'max:2048'],
            fn ($input) => $input->type === 'image'
        );

        return $validator;
    }

    public static function update(array $data)
    {
        return Validator::make($data, [
            'media_id' => ['required', 'exists:media,id'],
            'name' => ['required', 'string', 'max:20'],
            'group' => ['sometimes', 'string'],
        ]);
    }
}
