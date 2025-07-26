<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\ActivityHandler;
use App\Traits\MediaHandler;
use App\Traits\PartnerHandler;
use App\Traits\WorkdayHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

final class User extends Authenticatable implements JWTSubject
{
    use ActivityHandler;
    use HasFactory;
    use MediaHandler;
    use PartnerHandler;
    use WorkdayHandler;

    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier(): int|string
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
