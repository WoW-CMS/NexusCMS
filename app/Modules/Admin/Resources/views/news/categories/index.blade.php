@extends('admin::layouts.app')

@section('title', 'News Categories')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.news.index') }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">News Categories</h1>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.news.categories.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
            <i class="fas fa-plus mr-1"></i> New Category
        </a>
    </div>
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
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">All Categories</h2>
            <span class="text-sm text-gray-500">{{ $categories->total() }} categories</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Articles</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $category)
                    <tr class="{{ $category->trashed() ? 'bg-red-50/30 opacity-60' : 'hover:bg-gray-50' }} transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ $category->name }}</p>
                            @if($category->description)
                                <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $category->description }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $category->slug }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $category->news_count }}</td>
                        <td class="px-6 py-4">
                            @if($category->trashed())
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Deleted</span>
                            @elseif($category->is_active)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-500">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $category->order }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if(!$category->trashed())
                                    <a href="{{ route('admin.news.categories.edit', $category) }}" class="px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-xs font-medium">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.news.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-50 text-red-700 rounded hover:bg-red-100 text-xs font-medium">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-tags text-3xl mb-2 block"></i>
                            No categories found. <a href="{{ route('admin.news.categories.create') }}" class="text-blue-600 hover:underline">Create the first one</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $categories->links() }}
        </div>
        @endif
    </div>
</main>
@endsection
