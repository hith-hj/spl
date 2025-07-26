<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\PartnersTypes;
use App\Models\Partner;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class IsTrainerCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user instanceof Partner || $user->type !== PartnersTypes::trainer->name) {
            return Error(msg: 'Not Trainer, Un Authorized Action', code: 403);
        }

        return $next($request);
    }
}
