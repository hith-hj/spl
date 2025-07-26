<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class CourtResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'is_outdoor' => $this->is_outdoor,
            'is_main' => $this->is_main,
            'is_active' => $this->is_active,
            'name' => $this->name,
            'type' => $this->type,
            'phone' => $this->phone,
            'description' => $this->description,
            'workdays' => WorkdayResource::collection($this->whenLoaded('workdays')),
            'activities' => ActivityResource::collection($this->whenLoaded('activities')),
            'medias' => MediaResource::collection($this->whenLoaded('medias')),
            'location' => LocationResource::make($this->whenLoaded('location')),
            // 'appointments' => AppointmentResource::collection($this->whenLoaded('appointments')),
        ];
    }
}
