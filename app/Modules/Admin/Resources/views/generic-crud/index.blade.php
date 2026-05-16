@extends('admin::layouts.app')

@section('title', $config['title'] ?? 'Manage')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-800">{{ $config['title'] ?? 'Manage' }}</h1>
    </div>
    <a href="{{ route($base . 'create') }}"
       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
        <i class="fas fa-plus mr-1"></i> New Record
    </a>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 border border-green-200 rounded-lg flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-50 text-red-700 border border-red-200 rounded-lg flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between gap-4">
            <h2 class="text-lg font-bold text-gray-800">Records</h2>
            {{-- Search --}}
            @if(!empty($config['list']['searchable']))
            <form method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search…"
                       class="rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 w-56">
                <button type="submit" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                    <i class="fas fa-search"></i>
                </button>
                @if(request('search'))
                <a href="{{ route($base . 'index') }}" class="px-3 py-2 text-gray-400 hover:text-gray-600 text-sm">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </form>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        @foreach($config['list']['columns'] as $col)
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                {{ $col['label'] ?? $col['field'] }}
                            </th>
                        @endforeach
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($records as $record)
                    <tr class="hover:bg-gray-50 transition-colors">
                        @foreach($config['list']['columns'] as $col)
                            <td class="px-6 py-4 text-gray-700">
                                @php
                                    $value = $record->{$col['field']} ?? null;
                                    $format = $col['format'] ?? null;
                                @endphp
                                @if($format === 'date' && $value)
                                    {{ \Carbon\Carbon::parse($value)->format('M d, Y') }}
                                @elseif($format === 'datetime' && $value)
                                    {{ \Carbon\Carbon::parse($value)->format('M d, Y H:i') }}
                                @elseif($format === 'boolean')
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $value ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $value ? 'Yes' : 'No' }}
                                    </span>
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        @endforeach
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route($base . 'edit', $record->getKey()) }}"
                                   class="px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-xs font-medium">
                                    Edit
                                </a>
                                <form method="POST"
                                      action="{{ route($base . 'destroy', $record->getKey()) }}"
                                      onsubmit="return confirm('Delete this record?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1 bg-red-50 text-red-700 rounded hover:bg-red-100 text-xs font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($config['list']['columns']) + 1 }}"
                            class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-inbox text-3xl mb-2 block"></i>
                            No records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $records->links() }}
        </div>
        @endif
    </div>
</main>
@endsection
