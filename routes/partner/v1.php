<?php

declare(strict_types=1);

use App\Http\Controllers\Partner\Api\ActivitiesController;
use App\Http\Controllers\Partner\Api\AuthController;
use App\Http\Controllers\Partner\Api\CoursesController;
use App\Http\Controllers\Partner\Api\CourtsController;
use App\Http\Controllers\Partner\Api\LocationsController;
use App\Http\Controllers\Partner\Api\MediasController;
use App\Http\Controllers\Partner\Api\PartnersController;
use App\Http\Controllers\Partner\Api\WorkdaysController;
use App\Http\Middleware\Auth\JwtMiddleware;
use App\Http\Middleware\IsBadgedCheck;
use App\Http\Middleware\IsPartnerCheck;
use App\Http\Middleware\IsStadiumCheck;
use App\Http\Middleware\IsTrainerCheck;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'auth',
        'controller' => AuthController::class,
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

        Route::post('logout', 'logout')->name('logout');
        Route::post('changePassword', 'changePassword')->name('changePassword');
    }
);

Route::group(
    [
        'prefix' => 'partners',
        'controller' => PartnersController::class,
        'middleware' => [IsPartnerCheck::class],
    ],
    function (): void {
        Route::get('get', 'get');
        Route::post('stadium', 'createStadium');
        Route::post('trainer', 'createTrainer');
        Route::patch('stadium', 'updateStadium');
        Route::patch('trainer', 'updateTrainer');
        Route::patch('update', 'update');
        Route::delete('delete', 'delete');
    }
);

Route::group(
    [
        'prefix' => 'courts',
        'controller' => CourtsController::class,
        'middleware' => [IsPartnerCheck::class, IsStadiumCheck::class],
    ],
    function (): void {
        Route::get('all', 'all');
        Route::get('main', 'main');
        Route::get('find', 'find');
        Route::post('create', 'create');
        Route::post('setMain', 'setMain');
        Route::patch('update', 'update');
        Route::delete('delete', 'delete');
        Route::post('toggleActivation', 'toggleActivation');
    }
);

Route::group(
    [
        'prefix' => 'courses',
        'controller' => CoursesController::class,
        'middleware' => [IsPartnerCheck::class, IsTrainerCheck::class],
    ],
    function (): void {
        Route::get('all', 'all');
        Route::get('main', 'main');
        Route::get('find', 'find');
        Route::post('create', 'create');
        Route::post('setMain', 'setMain');
        Route::patch('update', 'update');
        Route::delete('delete', 'delete');
        Route::post('toggleActivation', 'toggleActivation');
    }
);

Route::group(
    [
        'prefix' => 'workdays',
        'controller' => WorkdaysController::class,
        'middleware' => [IsPartnerCheck::class, IsBadgedCheck::class],
    ],
    function (): void {
        Route::get('all', 'all');
        Route::get('find', 'find');
        Route::post('create', 'create');
        Route::patch('update', 'update');
        Route::delete('delete', 'delete');
        Route::post('toggleActivation', 'toggleActivation');
    }
);

Route::group(
    [
        'prefix' => 'activities',
        'controller' => ActivitiesController::class,
        'middleware' => [IsPartnerCheck::class, IsBadgedCheck::class],
    ],
    function (): void {
        Route::get('all', 'all');
        Route::get('find', 'find');
        Route::post('create', 'create');
        Route::patch('update', 'update');
        Route::delete('delete', 'delete');
        Route::post('toggleActivation', 'toggleActivation');
    }
);

Route::group(
    [
        'prefix' => 'medias',
        'controller' => MediasController::class,
        'middleware' => [IsPartnerCheck::class, IsBadgedCheck::class],
    ],
    function (): void {
        Route::get('all', 'all');
        Route::post('create', 'create');
        Route::patch('update', 'update');
        Route::get('find', 'find');
        Route::get('findByName', 'findByName');
        Route::get('findByGroup', 'findByGroup');
        Route::delete('delete', 'delete');
        Route::delete('deleteByGroup', 'deleteByGroup');
    }
);

Route::group(
    [
        'prefix' => 'locations',
        'controller' => LocationsController::class,
        'middleware' => [IsPartnerCheck::class, IsBadgedCheck::class],
    ],
    function (): void {
        Route::get('get', 'get');
        Route::post('create', 'create');
        Route::patch('update', 'update');
        Route::delete('delete', 'delete');
    }
);
