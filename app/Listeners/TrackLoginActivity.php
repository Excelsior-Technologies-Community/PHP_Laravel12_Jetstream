<?php

namespace App\Listeners;

use App\Models\LoginActivity;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class TrackLoginActivity
{
    /**
     * Handle the login event.
     */
    public function handleLogin(Login $event): void
    {
        $request = request();

        $userAgent = $request->userAgent();

        LoginActivity::create([
            'user_id' => $event->user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'browser' => $this->detectBrowser($userAgent),
            'device' => $this->detectDevice($userAgent),
            'login_at' => now(),
            'session_id' => $request->hasSession()
                ? $request->session()->getId()
                : null,
        ]);
    }

    /**
     * Handle the logout event.
     */
    public function handleLogout(Logout $event): void
    {
        if (!$event->user) {
            return;
        }

        $request = request();

        $activity = $event->user
            ->loginActivities()
            ->whereNull('logout_at')
            ->when(
                $request->hasSession(),
                fn ($query) => $query->where(
                    'session_id',
                    $request->session()->getId()
                )
            )
            ->latest('login_at')
            ->first();

        if (!$activity) {
            $activity = $event->user
                ->loginActivities()
                ->whereNull('logout_at')
                ->latest('login_at')
                ->first();
        }

        if ($activity) {
            $activity->update([
                'logout_at' => now(),
            ]);
        }
    }

    /**
     * Detect browser from user agent.
     */
    protected function detectBrowser(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown Browser';
        }

        return match (true) {
            str_contains($userAgent, 'Edg/') => 'Microsoft Edge',
            str_contains($userAgent, 'OPR/') => 'Opera',
            str_contains($userAgent, 'Opera/') => 'Opera',
            str_contains($userAgent, 'Chrome/') => 'Google Chrome',
            str_contains($userAgent, 'Firefox/') => 'Mozilla Firefox',
            str_contains($userAgent, 'Safari/')
                && !str_contains($userAgent, 'Chrome/')
                && !str_contains($userAgent, 'Edg/') => 'Safari',
            str_contains($userAgent, 'MSIE'),
            str_contains($userAgent, 'Trident/') => 'Internet Explorer',
            default => 'Unknown Browser',
        };
    }

    /**
     * Detect device from user agent.
     */
    protected function detectDevice(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown Device';
        }

        return match (true) {
            str_contains($userAgent, 'iPhone') => 'iPhone',
            str_contains($userAgent, 'iPad') => 'iPad',
            str_contains($userAgent, 'Android') => 'Android',
            str_contains($userAgent, 'Windows') => 'Windows PC',
            str_contains($userAgent, 'Macintosh'),
            str_contains($userAgent, 'Mac OS X') => 'Mac',
            str_contains($userAgent, 'Linux') => 'Linux PC',
            default => 'Unknown Device',
        };
    }
}