<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class WorkdayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'day' => $this->day,
            'from' => $this->from,
            'to' => $this->to,
            'is_active' => $this->is_active,
            // 'slots' => $this->whenLoaded('slots'),
            'slots' => $this->whenLoaded('slots'),
        ];
    }
}
