@extends('admin::layouts.app')

@section('title', 'Analytics')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Analytics</h1>
    </div>
    <div class="flex items-center gap-3">
        <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="7d">Last 7 Days</option>
            <option value="30d" selected>Last 30 Days</option>
            <option value="90d">Last 90 Days</option>
            <option value="1y">Last Year</option>
        </select>
    </div>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex items-center gap-3">
            <i class="fas fa-check-circle text-green-600"></i>
            <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-600"></i>
            <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Key Metrics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Visitors --}}
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Visitors</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalVisitors ?? 0 }}</p>
                <p class="text-xs text-green-600 mt-1"><i class="fas fa-arrow-up"></i> +12.5%</p>
            </div>
        </div>

        {{-- Page Views --}}
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-eye text-purple-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Page Views</p>
                <p class="text-2xl font-bold text-gray-800">{{ $pageViews ?? 0 }}</p>
                <p class="text-xs text-green-600 mt-1"><i class="fas fa-arrow-up"></i> +8.3%</p>
            </div>
        </div>

        {{-- Average Session Duration --}}
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-orange-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Avg. Session Duration</p>
                <p class="text-2xl font-bold text-gray-800">{{ $avgSessionDuration ?? '0:00' }}</p>
                <p class="text-xs text-red-600 mt-1"><i class="fas fa-arrow-down"></i> -2.1%</p>
            </div>
        </div>

        {{-- Bounce Rate --}}
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-sign-out-alt text-red-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Bounce Rate</p>
                <p class="text-2xl font-bold text-gray-800">{{ $bounceRate ?? '0' }}%</p>
                <p class="text-xs text-green-600 mt-1"><i class="fas fa-arrow-down"></i> -3.2%</p>
            </div>
        </div>
    </div>

    {{-- Top Pages --}}
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">Top Pages</h2>
            <span class="text-sm text-gray-500">This period</span>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unique Visitors</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg. Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bounce Rate</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topPages ?? [] as $page)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-file-alt text-gray-400"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $page['title'] ?? 'Page Title' }}</p>
                                            <p class="text-xs text-gray-500">{{ $page['url'] ?? '/path/to/page' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ $page['views'] ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $page['unique_visitors'] ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $page['avg_duration'] ?? '0:00' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-900 font-medium">{{ $page['bounce_rate'] ?? '0' }}%</span>
                                        @if(($page['bounce_rate'] ?? 0) < 40)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Good
                                            </span>
                                        @elseif(($page['bounce_rate'] ?? 0) < 60)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Medium
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                High
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-chart-bar text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No data available</p>
                                    <p class="text-sm text-gray-400 mt-1">Analytics data will appear once tracking is enabled.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</main>
@endsection
