<?php

namespace App\Providers;

use App\Listeners\TrackLoginActivity;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(
            'Laravel\Fortify\Events\TwoFactorAuthenticationEnabled',
            function ($event): void {
                if (isset($event->user)) {
                    $event->user->sendSecurityNotification(
                        'Two-Factor Authentication Enabled',
                        'Two-factor authentication has been enabled on your account.',
                        'two_factor'
                    );
                }
            }
        );

        Event::listen(
            'Laravel\Fortify\Events\TwoFactorAuthenticationDisabled',
            function ($event): void {
                if (isset($event->user)) {
                    $event->user->sendSecurityNotification(
                        'Two-Factor Authentication Disabled',
                        'Two-factor authentication has been disabled on your account.',
                        'two_factor'
                    );
                }
            }
        );
    }
}