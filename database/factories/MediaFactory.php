<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
final class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $video = 'https://storage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4';
        $image = 'https://gravatar.com/avatar/2446e56faf955a916e606cb791da285c?s=400&d=robohash&r=x';
        $type = fake()->boolean();
        $media = $type ? $image : $video;
        $type = $type ? 'image' : 'vedio';

        return [
            'media' => $media,
            'type' => $type,
            'name' => fake()->name(),
            'group' => null,
        ];
    }
}
