<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SecurityController extends Controller
{
    /**
     * Display the security dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Recent Login Activities
        |--------------------------------------------------------------------------
        */

        $recentActivities = $user->loginActivities()
            ->oldest('login_at')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Active Sessions
        |--------------------------------------------------------------------------
        */

        $activeSessions = $user->loginActivities()
            ->whereNull('logout_at')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | API Tokens
        |--------------------------------------------------------------------------
        */

        $apiTokenCount = $user->tokens()->count();

        /*
        |--------------------------------------------------------------------------
        | Notifications
        |--------------------------------------------------------------------------
        */

        $notifications = $user->notifications()
            ->oldest()
            ->take(5)
            ->get();

        $unreadNotifications = $user->unreadNotifications()->count();

        /*
        |--------------------------------------------------------------------------
        | Passkeys
        |--------------------------------------------------------------------------
        */

        $hasPasskeys = $this->hasPasskeys($user->id);

        /*
        |--------------------------------------------------------------------------
        | Security Score
        |--------------------------------------------------------------------------
        */

        $securityScore = 0;

        if ($user->email_verified_at) {
            $securityScore += 25;
        }

        if ($user->two_factor_secret) {
            $securityScore += 25;
        }

        if ($hasPasskeys) {
            $securityScore += 25;
        }

        if ($apiTokenCount === 0) {
            $securityScore += 25;
        }

        return view('security.index', [
            'user' => $user,
            'recentActivities' => $recentActivities,
            'activeSessions' => $activeSessions,
            'apiTokenCount' => $apiTokenCount,
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications,
            'hasPasskeys' => $hasPasskeys,
            'securityScore' => $securityScore,
        ]);
    }

    /**
     * Determine whether the user has configured a passkey.
     */
    protected function hasPasskeys(int $userId): bool
    {
        if (!Schema::hasTable('webauthn_credentials')) {
            return false;
        }

        try {
            return \DB::table('webauthn_credentials')
                ->where('user_id', $userId)
                ->exists();
        } catch (\Throwable $exception) {
            return false;
        }
    }

    /**
     * Mark one notification as read.
     */
    public function markNotificationAsRead(
        Request $request,
        string $notification
    ) {
        $user = $request->user();

        $user->notifications()
            ->where('id', $notification)
            ->first()
            ?->markAsRead();

        return back()->with(
            'success',
            'Notification marked as read.'
        );
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsAsRead(Request $request)
    {
        $request->user()
            ->unreadNotifications
            ->markAsRead();

        return back()->with(
            'success',
            'All notifications marked as read.'
        );
    }
}