<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

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

            {{-- Success Message --}}
            @if (session('success'))

                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>

            @endif

            {{-- Security Score --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                    <div>

                        <h3 class="text-lg font-semibold text-gray-900">
                            Security Score
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Your account security is based on verification and security features.
                        </p>

                    </div>

                    <div class="text-right">

                        <div class="text-4xl font-bold text-indigo-600">
                            {{ $securityScore }}%
                        </div>

                        <p class="text-sm text-gray-500">
                            Security Level
                        </p>

                    </div>

                </div>

                <div class="mt-5 w-full bg-gray-200 rounded-full h-3">

                    <div
                        class="bg-indigo-600 h-3 rounded-full transition-all duration-500"
                        style="width: {{ $securityScore }}%"
                    ></div>

                </div>

            </div>

            {{-- Security Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Email --}}
                <div class="bg-white shadow-xl sm:rounded-lg p-6">

                    <p class="text-sm text-gray-500">
                        Email Verification
                    </p>

                    <div class="flex items-center justify-between mt-2">

                        <p class="text-lg font-semibold text-gray-900">
                            {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                        </p>

                        <span class="text-2xl">
                            {{ $user->email_verified_at ? '✓' : '!' }}
                        </span>

                    </div>

                </div>

                {{-- 2FA --}}
                <div class="bg-white shadow-xl sm:rounded-lg p-6">

                    <p class="text-sm text-gray-500">
                        Two-Factor Authentication
                    </p>

                    <div class="flex items-center justify-between mt-2">

                        <p class="text-lg font-semibold text-gray-900">
                            {{ $user->two_factor_secret ? 'Enabled' : 'Disabled' }}
                        </p>

                        <span class="text-2xl">
                            {{ $user->two_factor_secret ? '✓' : '!' }}
                        </span>

                    </div>

                </div>

                {{-- Passkeys --}}
                <div class="bg-white shadow-xl sm:rounded-lg p-6">

                    <p class="text-sm text-gray-500">
                        Passkeys
                    </p>

                    <div class="flex items-center justify-between mt-2">

                        <p class="text-lg font-semibold text-gray-900">
                            {{ $hasPasskeys ? 'Configured' : 'Not Configured' }}
                        </p>

                        <span class="text-2xl">
                            {{ $hasPasskeys ? '✓' : '!' }}
                        </span>

                    </div>

                </div>

                {{-- API Tokens --}}
                <div class="bg-white shadow-xl sm:rounded-lg p-6">

                    <p class="text-sm text-gray-500">
                        API Tokens
                    </p>

                    <div class="flex items-center justify-between mt-2">

                        <p class="text-lg font-semibold text-gray-900">
                            {{ $apiTokenCount }}
                        </p>

                        <span class="text-2xl">
                            #
                        </span>

                    </div>

                </div>

            </div>

            {{-- Active Sessions --}}
            <div class="bg-white shadow-xl sm:rounded-lg p-6">

                <div class="flex items-center justify-between">

                    <div>

                        <h3 class="text-lg font-semibold text-gray-900">
                            Active Login Sessions
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Login sessions that do not have a logout record.
                        </p>

                    </div>

                    <div class="text-3xl font-bold text-green-600">
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

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                        <div>

                            <h3 class="text-lg font-semibold text-gray-900">
                                Security Notifications
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Recent security-related changes to your account.
                            </p>

                        </div>

                        @if ($unreadNotifications > 0)

                            <form
                                method="POST"
                                action="{{ route('security.notifications.read-all') }}"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="text-sm text-indigo-600 hover:text-indigo-900 font-semibold"
                                >
                                    Mark All as Read
                                </button>

                            </form>

                        @endif

                    </div>

                </div>

                @if ($notifications->count())

                    <div class="divide-y divide-gray-200">

                        @foreach ($notifications as $notification)

                            <div class="p-6 {{ $notification->read_at ? 'bg-white' : 'bg-indigo-50' }}">

                                <div class="flex items-start justify-between gap-4">

                                    <div>

                                        <div class="flex items-center gap-2">

                                            <h4 class="font-semibold text-gray-900">
                                                {{ $notification->data['title'] ?? 'Security Alert' }}
                                            </h4>

                                            @if (!$notification->read_at)

                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                    New
                                                </span>

                                            @endif

                                        </div>

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