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

        $recentActivities = $user->loginActivities()
            ->latest('login_at')
            ->take(10)
            ->get();

        $activeSessions = $user->loginActivities()
            ->whereNull('logout_at')
            ->count();

        $apiTokenCount = $user->tokens()->count();

        $notifications = $user->notifications()
            ->latest()
            ->take(10)
            ->get();

        $hasPasskeys = $this->hasPasskeys($user->id);

        return view('security.index', [
            'user' => $user,
            'recentActivities' => $recentActivities,
            'activeSessions' => $activeSessions,
            'apiTokenCount' => $apiTokenCount,
            'notifications' => $notifications,
            'hasPasskeys' => $hasPasskeys,
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
     * Mark a notification as read.
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

        return back();
    }
}