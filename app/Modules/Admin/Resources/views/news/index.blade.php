@extends('admin::layouts.app')

@section('title', 'News')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-800">News Management</h1>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.news.categories.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm font-medium">
            <i class="fas fa-tags mr-1"></i> Categories
        </a>
        <a href="{{ route('admin.news.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
            <i class="fas fa-plus mr-1"></i> New Article
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
            <h2 class="text-lg font-bold text-gray-800">All Articles</h2>
            <span class="text-sm text-gray-500">{{ $news->total() }} articles</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($news as $article)
                    <tr class="{{ $article->trashed() ? 'bg-red-50/30 opacity-60' : 'hover:bg-gray-50' }} transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($article->image)
                                    <img src="{{ asset('storage/images/' . $article->image) }}" alt="" class="w-10 h-10 rounded object-cover flex-shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded bg-gray-100 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-newspaper text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-800 line-clamp-1">{{ $article->title }}</p>
                                    <p class="text-xs text-gray-400 font-mono">/news/{{ $article->slug }}</p>
                                    @if($article->title_translations)
                                        <div class="flex gap-1 mt-1">
                                            @foreach(array_keys($article->title_translations) as $locale)
                                                <span class="text-[10px] px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded font-mono">{{ $locale }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $article->category->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $article->author->name ?? '—' }}</td>
                        <td class="px-6 py-4">
                            @if($article->trashed())
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    <i class="fas fa-trash text-[10px]"></i> Deleted
                                </span>
                            @elseif($article->is_published)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <i class="fas fa-circle text-[8px]"></i> Published
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                    <i class="fas fa-circle text-[8px]"></i> Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs whitespace-nowrap">
                            {{ $article->published_at ? $article->published_at->format('M d, Y') : $article->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($article->trashed())
                                    <form method="POST" action="{{ route('admin.news.restore', $article->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 text-xs font-medium">
                                            Restore
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.news.edit', $article) }}" class="px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-xs font-medium">
                                        Edit
                                    </a>
                                    <a href="{{ route('news.show', $article->slug) }}" target="_blank" class="px-3 py-1 bg-blue-50 text-blue-700 rounded hover:bg-blue-100 text-xs font-medium">
                                        View
                                    </a>
                                    <form method="POST" action="{{ route('admin.news.destroy', $article) }}" onsubmit="return confirm('Delete this article?')">
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
                            <i class="fas fa-newspaper text-3xl mb-2 block"></i>
                            No news articles found. <a href="{{ route('admin.news.create') }}" class="text-blue-600 hover:underline">Create the first one</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($news->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $news->links() }}
        </div>
        @endif
    </div>
</main>
@endsection
