<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Security Dashboard') }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Manage and monitor your account security.
                </p>
            </div>

            <a
                href="{{ route('security.login-activity') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
            >
                Login Activity
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Security Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Email Verification --}}
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">
                                Email Verification
                            </p>

                            <p class="text-lg font-semibold text-gray-900 mt-2">
                                {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                            </p>
                        </div>

                        <div class="text-2xl">
                            {{ $user->email_verified_at ? '✓' : '!' }}
                        </div>
                    </div>
                </div>

                {{-- Two Factor Authentication --}}
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">
                                Two-Factor Authentication
                            </p>

                            <p class="text-lg font-semibold text-gray-900 mt-2">
                                {{ $user->two_factor_secret ? 'Enabled' : 'Disabled' }}
                            </p>
                        </div>

                        <div class="text-2xl">
                            {{ $user->two_factor_secret ? '✓' : '!' }}
                        </div>
                    </div>
                </div>

                {{-- Passkeys --}}
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">
                                Passkeys
                            </p>

                            <p class="text-lg font-semibold text-gray-900 mt-2">
                                {{ $hasPasskeys ? 'Configured' : 'Not Configured' }}
                            </p>
                        </div>

                        <div class="text-2xl">
                            {{ $hasPasskeys ? '✓' : '!' }}
                        </div>
                    </div>
                </div>

                {{-- API Tokens --}}
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">
                                API Tokens
                            </p>

                            <p class="text-lg font-semibold text-gray-900 mt-2">
                                {{ $apiTokenCount }}
                            </p>
                        </div>

                        <div class="text-2xl">
                            #
                        </div>
                    </div>
                </div>

            </div>

            {{-- Active Sessions --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Active Login Sessions
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Number of login sessions currently without a logout record.
                        </p>
                    </div>

                    <div class="text-3xl font-bold text-gray-900">
                        {{ $activeSessions }}
                    </div>
                </div>
            </div>

            {{-- Recent Login Activity --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Recent Login Activity
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Your latest successful login sessions.
                            </p>
                        </div>

                        <a
                            href="{{ route('security.login-activity') }}"
                            class="text-sm text-indigo-600 hover:text-indigo-900"
                        >
                            View All
                        </a>
                    </div>
                </div>

                @if ($recentActivities->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Browser
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Device
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        IP Address
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Login Time
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Status
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">

                                @foreach ($recentActivities as $activity)
                                    <tr>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $activity->browser ?? 'Unknown' }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $activity->device ?? 'Unknown' }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $activity->ip_address ?? 'Unknown' }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $activity->login_at?->format('d M Y, h:i A') }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @if (!$activity->logout_at)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Logged Out
                                                </span>
                                            @endif

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>
                    </div>
                @else
                    <div class="p-6 text-sm text-gray-500">
                        No login activity found.
                    </div>
                @endif

            </div>

            {{-- Security Notifications --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Security Notifications
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Recent security-related changes to your account.
                    </p>
                </div>

                @if ($notifications->count())

                    <div class="divide-y divide-gray-200">

                        @foreach ($notifications as $notification)

                            <div class="p-6 {{ $notification->read_at ? 'bg-white' : 'bg-indigo-50' }}">

                                <div class="flex items-start justify-between gap-4">

                                    <div>
                                        <h4 class="font-semibold text-gray-900">
                                            {{ $notification->data['title'] ?? 'Security Alert' }}
                                        </h4>

                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $notification->data['message'] ?? '' }}
                                        </p>

                                        <p class="text-xs text-gray-400 mt-2">
                                            {{ $notification->created_at->format('d M Y, h:i A') }}
                                        </p>
                                    </div>

                                    @if (!$notification->read_at)

                                        <form
                                            method="POST"
                                            action="{{ route('security.notifications.read', $notification->id) }}"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="text-sm text-indigo-600 hover:text-indigo-900"
                                            >
                                                Mark as read
                                            </button>
                                        </form>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="p-6 text-sm text-gray-500">
                        No security notifications yet.
                    </div>

                @endif

            </div>

        </div>
    </div>
</x-app-layout>