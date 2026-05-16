@extends('admin::layouts.app')

@section('title', 'Forum Management')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <h1 class="text-2xl font-bold text-gray-800">Forum Management</h1>
    <a href="{{ route('admin.forum.create') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        <i class="fas fa-plus"></i> New Forum
    </a>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($forums as $forum)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $forum->name }}</td>
                        <td class="px-6 py-3 text-gray-500 font-mono text-xs">{{ $forum->slug }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $forum->parent?->name ?? '—' }}</td>
                        <td class="px-6 py-3">
                            @if($forum->is_category)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">Category</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Forum</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-gray-500">{{ $forum->order }}</td>
                        <td class="px-6 py-3 text-right space-x-2">
                            <a href="{{ route('admin.forum.edit', $forum) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                            <form method="POST" action="{{ route('admin.forum.destroy', $forum) }}" class="inline"
                                  onsubmit="return confirm('Delete this forum?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">No forums yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection
