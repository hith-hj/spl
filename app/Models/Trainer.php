<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\PartnerHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Trainer extends Model
{
    use HasFactory;
    use PartnerHandler;
}
