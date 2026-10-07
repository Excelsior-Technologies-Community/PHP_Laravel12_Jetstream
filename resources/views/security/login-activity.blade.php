<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Login Activity') }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Review and manage your account login sessions.
                </p>
            </div>

            <div class="flex gap-2">

                <a
                    href="{{ route('security') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                >
                    Security Dashboard
                </a>

                <a
                    href="{{ route('security.login-activity.export') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700"
                >
                    Export CSV
                </a>

            </div>

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

            {{-- Statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white shadow-xl sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">
                        Total Logins
                    </p>

                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        {{ $totalActivities }}
                    </p>
                </div>

                <div class="bg-white shadow-xl sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">
                        Active Sessions
                    </p>

                    <p class="text-3xl font-bold text-green-600 mt-2">
                        {{ $activeActivities }}
                    </p>
                </div>

                <div class="bg-white shadow-xl sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">
                        Logged Out
                    </p>

                    <p class="text-3xl font-bold text-gray-600 mt-2">
                        {{ $loggedOutActivities }}
                    </p>
                </div>

            </div>

            {{-- Filters --}}
            <div class="bg-white shadow-xl sm:rounded-lg p-6">

                <form
                    method="GET"
                    action="{{ route('security.login-activity') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4"
                >

                    {{-- Search --}}
                    <div class="lg:col-span-2">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Browser, device or IP address..."
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>

                    {{-- Status --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                            <option value="">All</option>

                            <option
                                value="active"
                                @selected(request('status') === 'active')
                            >
                                Active
                            </option>

                            <option
                                value="logged_out"
                                @selected(request('status') === 'logged_out')
                            >
                                Logged Out
                            </option>

                        </select>

                    </div>

                    {{-- From --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            From
                        </label>

                        <input
                            type="date"
                            name="date_from"
                            value="{{ request('date_from') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>

                    {{-- To --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            To
                        </label>

                        <input
                            type="date"
                            name="date_to"
                            value="{{ request('date_to') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                    </div>

                    <div class="lg:col-span-5 flex gap-2">

                        <button
                            type="submit"
                            class="px-5 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold hover:bg-indigo-700"
                        >
                            Apply Filters
                        </button>

                        <a
                            href="{{ route('security.login-activity') }}"
                            class="px-5 py-2 bg-gray-200 text-gray-800 rounded-md text-sm font-semibold hover:bg-gray-300"
                        >
                            Clear
                        </a>

                    </div>

                </form>

            </div>

            {{-- Login Table --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <form
                    method="POST"
                    action="{{ route('security.login-activity.bulk-destroy') }}"
                    id="bulkDeleteForm"
                >

                    @csrf
                    @method('DELETE')

                    <div class="p-6 border-b border-gray-200">

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <div>

                                <h3 class="text-lg font-semibold text-gray-900">
                                    Login History
                                </h3>

                                <p class="text-sm text-gray-500 mt-1">
                                    Showing {{ $activities->count() }} records on this page.
                                </p>

                            </div>

                            <button
                                type="submit"
                                id="bulkDeleteButton"
                                disabled
                                onclick="return confirm('Are you sure you want to delete the selected login activities?')"
                                class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-semibold hover:bg-red-700 disabled:opacity-40 disabled:cursor-not-allowed"
                            >
                                Delete Selected
                            </button>

                        </div>

                    </div>

                    @if ($activities->count())

                        <div class="overflow-x-auto">

                            <table class="min-w-full divide-y divide-gray-200">

                                <thead class="bg-gray-50">

                                    <tr>

                                        <th class="px-6 py-3 text-left">

                                            <input
                                                type="checkbox"
                                                id="selectAll"
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                            >

                                        </th>

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

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Action
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200">

                                    @foreach ($activities as $activity)

                                        <tr>

                                            {{-- Checkbox --}}
                                            <td class="px-6 py-4">

                                                <input
                                                    type="checkbox"
                                                    name="activities[]"
                                                    value="{{ $activity->id }}"
                                                    class="activity-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                >

                                            </td>

                                            {{-- Browser --}}
                                            <td class="px-6 py-4 whitespace-nowrap">

                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $activity->browser ?? 'Unknown Browser' }}
                                                </div>

                                            </td>

                                            {{-- Device --}}
                                            <td class="px-6 py-4 whitespace-nowrap">

                                                <div class="text-sm text-gray-900">
                                                    {{ $activity->device ?? 'Unknown Device' }}
                                                </div>

                                            </td>

                                            {{-- IP --}}
                                            <td class="px-6 py-4 whitespace-nowrap">

                                                <div class="text-sm text-gray-500">
                                                    {{ $activity->ip_address ?? 'Unknown' }}
                                                </div>

                                            </td>

                                            {{-- Login --}}
                                            <td class="px-6 py-4 whitespace-nowrap">

                                                <div class="text-sm text-gray-500">
                                                    {{ $activity->login_at?->format('d M Y, h:i A') }}
                                                </div>

                                            </td>

                                            {{-- Logout --}}
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

                                            {{-- Delete --}}
                                            <td class="px-6 py-4 whitespace-nowrap">

                                                <form
                                                    method="POST"
                                                    action="{{ route('security.login-activity.destroy', $activity) }}"
                                                    onsubmit="return confirm('Delete this login activity?')"
                                                >

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="text-red-600 hover:text-red-900 text-sm font-semibold"
                                                    >
                                                        Delete
                                                    </button>

                                                </form>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                        {{-- Numeric Pagination --}}
                        @if ($activities->hasPages())

                            <div class="p-6 border-t border-gray-200">

                                <div class="flex flex-wrap items-center gap-2">

                                    @for ($page = 1; $page <= $activities->lastPage(); $page++)

                                        @if (
                                            $page === 1 ||
                                            $page === $activities->lastPage() ||
                                            abs($page - $activities->currentPage()) <= 2
                                        )

                                            <a
                                                href="{{ $activities->url($page) }}"
                                                class="px-3 py-2 rounded-md text-sm font-medium
                                                {{ $page === $activities->currentPage()
                                                    ? 'bg-indigo-600 text-white'
                                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                                            >
                                                {{ $page }}
                                            </a>

                                        @elseif (
                                            $page === 2 ||
                                            $page === $activities->lastPage() - 1
                                        )

                                            <span class="px-2 text-gray-500">
                                                ...
                                            </span>

                                        @endif

                                    @endfor

                                </div>

                            </div>

                        @endif

                    @else

                        <div class="p-10 text-center">

                            <div class="text-4xl mb-3">
                                🔐
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900">
                                No Login Activity Found
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Try changing your search or filters.
                            </p>

                        </div>

                    @endif

                </form>

            </div>

        </div>

    </div>

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const selectAll = document.getElementById('selectAll');

            const checkboxes = document.querySelectorAll(
                '.activity-checkbox'
            );

            const deleteButton = document.getElementById(
                'bulkDeleteButton'
            );

            function updateDeleteButton() {

                const checked = document.querySelectorAll(
                    '.activity-checkbox:checked'
                ).length;

                deleteButton.disabled = checked === 0;

            }

            selectAll?.addEventListener('change', function () {

                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAll.checked;
                });

                updateDeleteButton();

            });

            checkboxes.forEach(function (checkbox) {

                checkbox.addEventListener('change', function () {

                    const checkedCount = document.querySelectorAll(
                        '.activity-checkbox:checked'
                    ).length;

                    selectAll.checked =
                        checkedCount === checkboxes.length;

                    selectAll.indeterminate =
                        checkedCount > 0 &&
                        checkedCount < checkboxes.length;

                    updateDeleteButton();

                });

            });

        });

    </script>

</x-app-layout>