<?php

declare(strict_types=1);

use App\Http\Middleware\Auth\JwtMiddleware;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'auth',
        'controller' => 'AuthController::class',
    ],
    function (): void {
        Route::withoutMiddleware([JwtMiddleware::class])->group(function (): void {
            Route::post('register', 'register')->name('register');
            Route::post('verify', 'verify')->name('verify');
            Route::post('login', 'login')->name('login');
            Route::post('forgetPassword', 'forgetPassword')->name('forgetPassword');
            Route::post('resetPassword', 'resetPassword')->name('resetPassword');
            Route::post('resendCode', 'resendCode')->name('resendCode');
            Route::get('refreshToken', 'refreshToken')->name('refreshToken');
        });

        Route::post('deleteUser', 'deleteUser')->name('deleteUser');
        Route::get('user', 'getUser')->name('getUser');
        Route::post('logout', 'logout')->name('logout');
        Route::post('changePassword', 'changePassword')->name('changePassword');
    }
);

Route::withoutMiddleware(JwtMiddleware::class)->group(function (): void {
    Route::group(
        [
            'prefix' => 'label',
            'controller' => 'LabelController::class,',
        ],
        function (): void {
            Route::get('carBrands', 'carBrands');
            Route::get('carColors', 'carColors');
            Route::get('items', 'items');
            Route::get('attrs', 'attrs');
        }
    );
});
