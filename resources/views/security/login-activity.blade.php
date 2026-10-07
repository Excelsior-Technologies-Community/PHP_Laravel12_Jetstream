<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Login Activity') }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Review your recent account login sessions.
                </p>
            </div>

            <a
                href="{{ route('security') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
            >
                Security Dashboard
            </a>

        </div>

    </x-slot>

    <div class="py-10">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 border-b border-gray-200">

                    <h3 class="text-lg font-semibold text-gray-900">
                        Login History
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Successful login sessions for your account.
                    </p>

                </div>

                @if ($activities->count())

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
                                        Logout Time
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Status
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">

                                @foreach ($activities as $activity)

                                    <tr>

                                        {{-- Browser --}}
                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $activity->browser ?? 'Unknown Browser' }}
                                            </div>

                                        </td>

                                        {{-- Device --}}
                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $activity->device ?? 'Unknown Device' }}
                                            </div>

                                        </td>

                                        {{-- IP Address --}}
                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="text-sm text-gray-500">
                                                {{ $activity->ip_address ?? 'Unknown' }}
                                            </div>

                                        </td>

                                        {{-- Login Time --}}
                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="text-sm text-gray-500">
                                                {{ $activity->login_at?->format('d M Y, h:i A') }}
                                            </div>

                                        </td>

                                        {{-- Logout Time --}}
                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="text-sm text-gray-500">

                                                @if ($activity->logout_at)

                                                    {{ $activity->logout_at->format('d M Y, h:i A') }}

                                                @else

                                                    —

                                                @endif

                                            </div>

                                        </td>

                                        {{-- Status --}}
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

                    <div class="p-6 border-t border-gray-200">
                        {{ $activities->links() }}
                    </div>

                @else

                    <div class="p-6 text-sm text-gray-500">
                        No login activity has been recorded yet.
                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>