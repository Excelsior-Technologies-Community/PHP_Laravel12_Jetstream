<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginActivityController extends Controller
{
    /**
     * Display all login activity for the authenticated user.
     */
    public function index(Request $request)
    {
        $activities = $request->user()
            ->loginActivities()
            ->latest('login_at')
            ->paginate(15);

        return view('security.login-activity', [
            'activities' => $activities,
        ]);
    }
}