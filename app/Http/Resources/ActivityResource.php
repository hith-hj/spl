<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ActivityResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'is_trial' => $this->is_trial,
            'trial_option' => $this->trial_option,
            'discount_amount' => $this->discount_amount,
            'cost' => $this->cost,
            'cancellation_cost' => $this->cancellation_cost,
            'is_private' => $this->is_private,
            'private_cost' => $this->private_cost,
        ];
    }
}
