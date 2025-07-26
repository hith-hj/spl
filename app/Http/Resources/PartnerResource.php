<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class PartnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'type' => $this->type,
            'details' => $this->details,
            'is_verified' => $this->verified_at,
            'is_active' => $this->is_active,
            'is_filled' => (int) $this->badge()->count() > 0,
            'created_at' => $this->created_at,
        ];
    }
}
