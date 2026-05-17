@extends('admin::layouts.app')

@section('title', $config['title'] ?? 'Manage')

@section('content')

{{-- Page header --}}
<div class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between gap-4 shrink-0">
    <div>
        <h1 class="text-xl font-bold text-gray-900">{{ $config['title'] ?? 'Manage' }}</h1>
        @if(!empty($config['description']))
            <p class="text-sm text-gray-500 mt-0.5">{{ $config['description'] }}</p>
        @endif
    </div>
    <a href="{{ route($base . 'create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg
              hover:bg-blue-700 active:bg-blue-800 transition-colors shadow-sm">
        <i class="fas fa-plus text-xs"></i>
        New {{ \Illuminate\Support\Str::singular($config['title'] ?? 'Record') }}
    </a>
</div>

<div class="flex-1 overflow-y-auto bg-gray-50 p-6">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            <i class="fas fa-check-circle text-green-500 shrink-0"></i>
            <span class="flex-1">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            <i class="fas fa-exclamation-circle text-red-500 shrink-0"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Table card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

        {{-- Toolbar --}}
        <div class="flex items-center justify-between gap-4 px-5 py-3 border-b border-gray-100">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span class="font-semibold text-gray-800">{{ $records->total() }}</span>
                {{ \Illuminate\Support\Str::lower($config['title'] ?? 'records') }}
                @if(request('search'))
                    <span class="text-gray-400">&mdash; matching <em>"{{ request('search') }}"</em></span>
                @endif
            </div>

            @php
                $searchEnabled = !empty($config['list']['searchable'])
                    || !empty($config['list']['search_columns']);
            @endphp
            @if($searchEnabled)
            <form method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search…"
                           class="pl-8 pr-8 py-1.5 rounded-lg border border-gray-200 bg-gray-50 text-sm w-52
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @if(request('search'))
                        <a href="{{ route($base . 'index') }}"
                           class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition"
                           title="Clear">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    @endif
                </div>
                <button type="submit"
                        class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                    Search
                </button>
            </form>
            @endif
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        @foreach($config['list']['columns'] as $col)
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                {{ $col['label'] ?? $col['field'] }}
                            </th>
                        @endforeach
                        <th class="px-5 py-3 w-20"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($records as $record)
                    <tr class="hover:bg-blue-50/20 transition-colors">
                        @foreach($config['list']['columns'] as $col)
                            <td class="px-5 py-3.5 text-gray-700 align-middle">
                                @php
                                    $cellValue  = $record->{$col['field']} ?? null;
                                    $cellFormat = $col['format'] ?? null;
                                @endphp
                                @if($cellFormat === 'date' && $cellValue)
                                    {{ \Carbon\Carbon::parse($cellValue)->format('M d, Y') }}
                                @elseif($cellFormat === 'datetime' && $cellValue)
                                    {{ \Carbon\Carbon::parse($cellValue)->format('M d, Y · H:i') }}
                                @elseif($cellFormat === 'boolean')
                                    @if($cellValue)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            <i class="fas fa-check text-[9px]"></i> Yes
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-400">
                                            <i class="fas fa-minus text-[9px]"></i> No
                                        </span>
                                    @endif
                                @elseif($cellFormat === 'image' && $cellValue)
                                    <img src="{{ asset('storage/' . $cellValue) }}"
                                         class="h-8 w-8 rounded-md object-cover border border-gray-200" alt="">
                                @elseif($cellFormat === 'badge' && $cellValue)
                                    <span class="px-2 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700">{{ $cellValue }}</span>
                                @elseif($cellFormat === 'truncate')
                                    <span class="line-clamp-1 max-w-xs text-gray-600" title="{{ $cellValue }}">
                                        {{ \Illuminate\Support\Str::limit($cellValue, 60) }}
                                    </span>
                                @else
                                    <span class="{{ $cellValue === null || $cellValue === '' ? 'text-gray-300' : '' }}">
                                        {{ $cellValue ?? '—' }}
                                    </span>
                                @endif
                            </td>
                        @endforeach
                        <td class="px-5 py-3.5 align-middle">
                            <div class="flex items-center justify-end gap-0.5">
                                <a href="{{ route($base . 'edit', $record->getKey()) }}"
                                   title="Edit"
                                   class="w-8 h-8 inline-flex items-center justify-center rounded-lg
                                          text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition">
                                    <i class="fas fa-pencil text-xs"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route($base . 'destroy', $record->getKey()) }}"
                                      onsubmit="return confirm('Delete this record? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            title="Delete"
                                            class="w-8 h-8 inline-flex items-center justify-center rounded-lg
                                                   text-gray-400 hover:text-red-600 hover:bg-red-50 transition">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($config['list']['columns']) + 1 }}"
                            class="px-6 py-16 text-center">
                            <div class="inline-flex flex-col items-center gap-3 text-gray-400">
                                <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-database text-xl text-gray-300"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-500 text-sm">No records found</p>
                                    @if(request('search'))
                                        <p class="text-xs mt-1 text-gray-400">
                                            Try a different search term or
                                            <a href="{{ route($base . 'index') }}" class="text-blue-500 hover:underline">clear the filter</a>.
                                        </p>
                                    @else
                                        <p class="text-xs mt-1 text-gray-400">
                                            <a href="{{ route($base . 'create') }}" class="text-blue-500 hover:underline">Create the first record</a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($records->hasPages())
        <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between gap-4 text-xs text-gray-500">
            <span>
                Showing {{ $records->firstItem() }}–{{ $records->lastItem() }} of {{ $records->total() }}
            </span>
            {{ $records->links() }}
        </div>
        @endif

    </div>
</div>

@endsection
