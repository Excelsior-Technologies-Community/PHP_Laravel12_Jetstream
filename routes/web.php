<?php

use App\Http\Controllers\LoginActivityController;
use App\Http\Controllers\SecurityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Security Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/security', [
        SecurityController::class,
        'index',
    ])->name('security');

    /*
    |--------------------------------------------------------------------------
    | Login Activity
    |--------------------------------------------------------------------------
    */

    Route::get('/security/login-activity', [
        LoginActivityController::class,
        'index',
    ])->name('security.login-activity');

    /*
    |--------------------------------------------------------------------------
    | Security Notifications
    |--------------------------------------------------------------------------
    */

    Route::post('/security/notifications/{notification}/read', [
        SecurityController::class,
        'markNotificationAsRead',
    ])->name('security.notifications.read');
});