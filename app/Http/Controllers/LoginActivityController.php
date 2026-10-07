<?php

namespace App\Http\Controllers;

use App\Models\LoginActivity;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LoginActivityController extends Controller
{
    /**
     * Display login activity.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Login Activity Query
        |--------------------------------------------------------------------------
        */
        $query = $user->loginActivities()
            ->latest('login_at');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('browser', 'like', "%{$search}%")
                    ->orWhere('device', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */
        if ($request->status === 'active') {

            $query->whereNull('logout_at');

        } elseif ($request->status === 'logged_out') {

            $query->whereNotNull('logout_at');
        }

        /*
        |--------------------------------------------------------------------------
        | Date From Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('date_from')) {

            $query->whereDate(
                'login_at',
                '>=',
                $request->date_from
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date To Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('date_to')) {

            $query->whereDate(
                'login_at',
                '<=',
                $request->date_to
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
        $activities = $query
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */
        $totalActivities = $user->loginActivities()
            ->count();

        $activeActivities = $user->loginActivities()
            ->whereNull('logout_at')
            ->count();

        $loggedOutActivities = $user->loginActivities()
            ->whereNotNull('logout_at')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */
        return view(
            'security.login-activity',
            compact(
                'activities',
                'totalActivities',
                'activeActivities',
                'loggedOutActivities'
            )
        );
    }

    /**
     * Delete individual login activity.
     */
    public function destroy(
        Request $request,
        LoginActivity $activity
    ) {
        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */
        if ($activity->user_id !== $request->user()->id) {

            abort(
                403,
                'Unauthorized action.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */
        $activity->delete();

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */
        return redirect()
            ->route('security.login-activity')
            ->with(
                'success',
                'Login activity deleted successfully.'
            );
    }

    /**
     * Bulk delete login activities.
     */
    public function bulkDestroy(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */
        $request->validate([
            'activities' => [
                'required',
                'array',
                'min:1',
            ],

            'activities.*' => [
                'integer',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Delete Selected Records
        |--------------------------------------------------------------------------
        */
        $deleted = LoginActivity::where(
                'user_id',
                $request->user()->id
            )
            ->whereIn(
                'id',
                $request->activities
            )
            ->delete();

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */
        return redirect()
            ->route('security.login-activity')
            ->with(
                'success',
                $deleted .
                ' login activity record(s) deleted successfully.'
            );
    }

    /**
     * Export login activities to CSV.
     */
    public function export(
        Request $request
    ): StreamedResponse {

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Get User Login Activities
        |--------------------------------------------------------------------------
        */
        $activities = $user->loginActivities()
            ->latest('login_at')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | CSV Filename
        |--------------------------------------------------------------------------
        */
        $filename =
            'login-activity-' .
            now()->format('Y-m-d-H-i-s') .
            '.csv';

        /*
        |--------------------------------------------------------------------------
        | Stream CSV
        |--------------------------------------------------------------------------
        */
        return response()->streamDownload(

            function () use ($activities) {

                $handle = fopen(
                    'php://output',
                    'w'
                );

                /*
                |--------------------------------------------------------------------------
                | CSV Header
                |--------------------------------------------------------------------------
                */
                fputcsv($handle, [
                    'Browser',
                    'Device',
                    'IP Address',
                    'Login Time',
                    'Logout Time',
                    'Status',
                ]);

                /*
                |--------------------------------------------------------------------------
                | CSV Data
                |--------------------------------------------------------------------------
                */
                foreach ($activities as $activity) {

                    fputcsv($handle, [

                        $activity->browser,

                        $activity->device,

                        $activity->ip_address,

                        $activity->login_at
                            ? $activity->login_at
                                ->format('Y-m-d H:i:s')
                            : '',

                        $activity->logout_at
                            ? $activity->logout_at
                                ->format('Y-m-d H:i:s')
                            : '',

                        $activity->logout_at
                            ? 'Logged Out'
                            : 'Active',
                    ]);
                }

                fclose($handle);
            },

            $filename,

            [
                'Content-Type' => 'text/csv',
            ]
        );
    }
}