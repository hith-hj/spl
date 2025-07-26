<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\PartnerHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Stadium extends Model
{
    use HasFactory;
    use PartnerHandler;

    // this is here becaus of a bug in the pluralization of the table name
    // when creating the model with migration `php artisan make:model -Stadium -mf`
    protected $table = 'stadiums';
}
