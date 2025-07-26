<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Partner;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class IsPartnerCheck
{
    public function handle(Request $request, Closure $next): Response
    {

        if ($request->user() === null) {
            return Error(msg: 'Un Authorized user', code: 401);
        }

        if (! $request->user() instanceof Partner) {
            return Error(msg: 'Not Partner,Un Authorized Action', code: 403);
        }

        if ($request->user()->verified_at === null) {
            return Error(msg: 'Un verified partner account', code: 403);
        }

        return $next($request);
    }
}
