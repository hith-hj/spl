<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PartnersTypes;
use App\Traits\CategoryHandler;
use App\Traits\CodeHandler;
use App\Traits\NotificationsHandler;
use App\Traits\VerificationHandler;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

final class Partner extends Authenticatable implements JWTSubject
{
    use CategoryHandler;
    use CodeHandler;
    use HasFactory;
    use NotificationsHandler;
    use VerificationHandler;

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function getJWTIdentifier(): int|string
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return ['type' => $this->type];
    }

    public function details(): HasOne
    {
        return match ($this->type) {
            PartnersTypes::stadium->name => $this->hasOne(Stadium::class),
            PartnersTypes::trainer->name => $this->hasOne(Trainer::class),
            default => throw new Exception("Invalid type: $this->type for details")
        };
    }

    public function badge(): HasOne
    {
        $badge = match ($this->type) {
            PartnersTypes::stadium->name => $this->courts(),
            PartnersTypes::trainer->name => $this->courses(),
            default => throw new Exception("Invalid type: $this->type ")
        };

        return $badge->one()->where('is_main', true);
    }

    public function courts(): HasMany
    {
        if ($this->type !== PartnersTypes::stadium->name) {
            throw new Exception('Not Stadium, No courts');
        }

        return $this->hasMany(Court::class);
    }

    public function courses(): HasMany
    {
        if ($this->type !== PartnersTypes::trainer->name) {
            throw new Exception('Not Trainer, No courses');
        }

        return $this->hasMany(Course::class);
    }
}
