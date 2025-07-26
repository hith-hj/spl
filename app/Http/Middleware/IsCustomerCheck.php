<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class IsCustomerCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        // if (! $request->user() instanceof Customer) {
        //     return Error(msg: 'Not Customer, Un Authorized Action', code: 403);
        // }

        return $next($request);
    }
}
