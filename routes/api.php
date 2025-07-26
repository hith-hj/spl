<?php

declare(strict_types=1);

use App\Http\Controllers\LabelController;
use App\Http\Middleware\Auth\JwtMiddleware;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'label',
        'controller' => LabelController::class,
        'middleware' => ['throttle:2,1'],
    ],
    function (): void {
        Route::get('partnersTypes', 'partnersTypes');
        Route::get('courtsTypes', 'courtsTypes');
        Route::get('trainersTypes', 'trainersTypes');
        Route::get('slotsDurations', 'slotsDurations');
    }
);

Route::group(
    [
        'prefix' => '/partner',
        'middleware' => [JwtMiddleware::class]
    ],
    function (): void {
        Route::group(
            [
                'prefix' => '/v1'
            ],
            function (): void {
                require 'partner/v1.php';
            }
        );
    }
);
