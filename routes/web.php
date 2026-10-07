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

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

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
    | Login Activity Delete
    |--------------------------------------------------------------------------
    */

    Route::delete('/security/login-activity/{activity}', [
        LoginActivityController::class,
        'destroy',
    ])->name('security.login-activity.destroy');

    /*
    |--------------------------------------------------------------------------
    | Bulk Delete Login Activity
    |--------------------------------------------------------------------------
    */

    Route::delete('/security/login-activity', [
        LoginActivityController::class,
        'bulkDestroy',
    ])->name('security.login-activity.bulk-destroy');

    /*
    |--------------------------------------------------------------------------
    | Export Login Activity
    |--------------------------------------------------------------------------
    */

    Route::get('/security/login-activity/export', [
        LoginActivityController::class,
        'export',
    ])->name('security.login-activity.export');

    /*
    |--------------------------------------------------------------------------
    | Security Notifications
    |--------------------------------------------------------------------------
    */

    Route::post('/security/notifications/{notification}/read', [
        SecurityController::class,
        'markNotificationAsRead',
    ])->name('security.notifications.read');

    Route::post('/security/notifications/read-all', [
        SecurityController::class,
        'markAllNotificationsAsRead',
    ])->name('security.notifications.read-all');
});