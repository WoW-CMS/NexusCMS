@extends('admin::layouts.app')

@section('title', 'Logs')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-800">System Logs</h1>
    </div>
    <div class="flex items-center gap-3">
        <form method="GET" action="{{ route('admin.logs.index') }}" class="flex items-center gap-3">
            {{-- Log file selector --}}
            <select name="file" onchange="this.form.submit()" class="pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                @foreach($logFiles as $file)
                    <option value="{{ $file }}" @selected($file === $selectedFile)>{{ $file }}</option>
                @endforeach
            </select>

            {{-- Level filter --}}
            <select name="level" onchange="this.form.submit()" class="pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">All Levels</option>
                @foreach(['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'] as $level)
                    <option value="{{ $level }}" @selected($level === $levelFilter)>{{ ucfirst($level) }}</option>
                @endforeach
            </select>

            {{-- Search --}}
            <div class="relative">
                <input type="search" name="search" value="{{ $searchQuery }}" placeholder="Search logs..."
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64 text-sm">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                Filter
            </button>
        </form>
    </div>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-alt text-blue-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Log Files</p>
                <p class="text-xl font-bold text-gray-800">{{ $logFiles->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-list text-gray-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Entries</p>
                <p class="text-xl font-bold text-gray-800">{{ $total }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-exclamation-circle text-red-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Current File</p>
                <p class="text-xl font-bold text-gray-800 truncate max-w-[140px]" title="{{ $selectedFile }}">{{ $selectedFile ?? 'None' }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-filter text-green-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Active Filter</p>
                <p class="text-xl font-bold text-gray-800">{{ $levelFilter ? ucfirst($levelFilter) : 'None' }}</p>
            </div>
        </div>
    </div>

    {{-- Log Entries --}}
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">Log Entries</h2>
            <span class="text-sm text-gray-500">
                Showing {{ $entries->count() }} of {{ $total }} entries
                @if($total > $perPage)
                    (Page {{ $page }} of {{ ceil($total / $perPage) }})
                @endif
            </span>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @forelse($entries as $entry)
                    @php
                        $levelColors = [
                            'emergency' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-500', 'badge' => 'bg-red-600'],
                            'alert'     => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-400', 'badge' => 'bg-red-500'],
                            'critical'  => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-400', 'badge' => 'bg-red-500'],
                            'error'     => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-300', 'badge' => 'bg-red-400'],
                            'warning'   => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-400', 'badge' => 'bg-yellow-500'],
                            'notice'    => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-300', 'badge' => 'bg-blue-400'],
                            'info'      => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-300', 'badge' => 'bg-blue-500'],
                            'debug'     => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'border' => 'border-gray-300', 'badge' => 'bg-gray-400'],
                        ];
                        $colors = $levelColors[$entry['level']] ?? $levelColors['debug'];

                        $lines = explode("\n", $entry['message']);
                        $firstLine = $lines[0];
                        $stackTrace = count($lines) > 1 ? implode("\n", array_slice($lines, 1)) : null;
                    @endphp

                    <div class="p-4 {{ $colors['bg'] }} rounded-lg border-l-4 {{ $colors['border'] }}">
                        <div class="flex items-start justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 {{ $colors['badge'] }} text-white rounded text-xs font-semibold uppercase">
                                    {{ $entry['level'] }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>{{ $entry['timestamp'] }}
                                </span>
                            </div>
                        </div>
                        <p class="text-sm {{ $colors['text'] }} font-medium mt-2 break-all">{{ $firstLine }}</p>

                        @if($stackTrace)
                            <details class="mt-2">
                                <summary class="text-xs text-gray-500 cursor-pointer hover:text-gray-700">
                                    <i class="fas fa-code mr-1"></i>Stack Trace
                                </summary>
                                <pre class="mt-2 p-3 bg-gray-900 text-gray-100 rounded text-xs overflow-x-auto max-h-64 overflow-y-auto"><code>{{ $stackTrace }}</code></pre>
                            </details>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-terminal text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 font-medium">No log entries found</p>
                        <p class="text-sm text-gray-400 mt-1">
                            @if($levelFilter || $searchQuery)
                                Try adjusting your filters or search query.
                            @else
                                No log files available.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($total > $perPage)
                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-500">
                        Showing {{ (($page - 1) * $perPage) + 1 }} to {{ min($page * $perPage, $total) }} of {{ $total }} entries
                    </div>
                    <div class="flex items-center gap-2">
                        @if($page > 1)
                            <a href="{{ route('admin.logs.index', array_merge(request()->query(), ['page' => $page - 1])) }}"
                                class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">
                                <i class="fas fa-chevron-left mr-1"></i> Previous
                            </a>
                        @endif
                        @if($page < ceil($total / $perPage))
                            <a href="{{ route('admin.logs.index', array_merge(request()->query(), ['page' => $page + 1])) }}"
                                class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                Next <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
