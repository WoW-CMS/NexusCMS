@extends('layouts.main')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-900 to-gray-950 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Alerts -->
            @if (session('success'))
                <div class="bg-green-900/30 border-l-4 border-green-500 backdrop-blur-sm p-5 mb-8 rounded-md">
                    <div class="flex items-center space-x-3">
                        <svg class="h-6 w-6 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm text-green-200 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <div
                        class="bg-gray-900/50 rounded-2xl overflow-hidden backdrop-blur-md shadow-lg border border-gray-800 p-6">
                        <!-- Featured Image with Enhanced Overlay -->
                        @if ($item->image)
                            <div class="relative h-96 overflow-hidden rounded-lg">
                                <img class="w-full h-48 md:h-full object-cover" 
                                    src="{{ asset('storage/images/' . $item->image) }}" alt="{{ $item->title }}">
                            </div>
                        @endif

                        <!-- Article Content -->
                        <div class="mt-6">
                            <div class="flex flex-wrap items-center space-x-4 text-gray-400 text-sm mb-4">
                                @if($item->category)
                                    <span class="px-3 py-1 bg-indigo-500/10 text-indigo-300 rounded-full">{{ $item->category->name }}</span>
                                @else
                                    <span class="px-3 py-1 bg-indigo-500/10 text-indigo-300 rounded-full">News</span>
                                @endif
                                <span class="flex items-center space-x-1">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $item->published_at ? $item->published_at->format('M d, Y') : 'Unpublished' }}</span>
                                </span>
                                <span class="flex items-center space-x-1">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $item->reading_time }} min read</span>
                                </span>
                            </div>
                            <h1 class="text-4xl font-bold text-white leading-tight">{{ $item->title }}</h1>
                        </div>

                        <!-- Enhanced Author Information -->
                        <div class="flex items-center space-x-4 mt-6 border-t border-gray-800 pt-6">
                            <img class="h-14 w-14 rounded-full ring-2 ring-indigo-500"
                                src="{{ $item->author->avatar ?? asset('images/default-avatar.png') }}" alt="Author">
                            <div>
                                <p class="text-white font-medium">{{ $item->author->name ?? 'Anonymous' }}</p>
                                <p class="text-sm text-gray-400">{{ $item->author->role ?? 'Editor' }}</p>
                            </div>
                        </div>

                        <!-- Enhanced Content Styles -->
                        <div class="prose prose-lg prose-invert max-w-none mt-6">
                            {!! $item->content !!}
                        </div>

                        <!-- Tags -->
                        <div class="mt-8 flex flex-wrap gap-2">
                            @if($item->category)
                                <span class="inline-flex items-center px-3 py-1 bg-indigo-500/10 text-indigo-300 rounded-full text-sm hover:bg-indigo-500/20 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    {{ $item->category->name }}
                                </span>
                            @endif
                        </div>

                        <!-- Share -->
                        <div class="mt-8 flex items-center space-x-4 text-gray-400">
                            <span class="text-sm">Share:</span>
                                @foreach(['twitter', 'facebook', 'pinterest', 'reddit'] as $social)
                                <div class="flex space-x-2">
                                    <a href="{{ app(App\Helpers\GeneralHelper::class)->shareSocial($social, url()->current(), $item->title)['url'] }}"
                                    target="_blank"
                                    class="text-gray-400 hover:text-white transition duration-300">
                                        <i class="{{ app(App\Helpers\GeneralHelper::class)->shareSocial($social, url()->current(), $item->title)['icon'] }} fa-lg"></i>
                                    </a>
                                </div>
                                @endforeach
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="mt-8 bg-gray-900/60 rounded-2xl backdrop-blur-md shadow-lg border border-gray-800 p-8">
                        <h3 class="text-2xl font-bold text-white mb-8 flex items-center gap-2">
                            Comments
                        </h3>
                        <!-- validate login -->
                        @auth
                        <form action="{{ route('news.comment.store', $item->slug) }}" method="POST" class="mb-10">
                            @csrf
                            <div class="flex items-start gap-4">
                                <img class="h-12 w-12 rounded-full ring-2 ring-indigo-500 mt-1"
                                    src="{{ asset('images/default-avatar.png') }}" alt="Your Avatar">
                                <div class="flex-1">
                                    <textarea id="editor" name="body" required
                                        class="w-full bg-gray-950/60 rounded-lg p-4 text-white focus:ring-2 focus:ring-indigo-500 border border-gray-800"
                                        placeholder="Write a comment..."></textarea>
                                    <div class="flex justify-end mt-2">
                                        <button type="submit"
                                            class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold shadow">Comment</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @endauth
                        <div class="space-y-8">
                            @forelse($item->comments as $comment)
                                <div class="flex items-start gap-4 group">
                                    {{-- Avatar --}}
                                    <img class="h-12 w-12 rounded-full ring-2 ring-indigo-500 mt-1"
                                        src="{{ $comment->user->avatar_url ?? asset('images/default-avatar.png') }}"
                                        alt="Avatar">

                                    <div class="flex-1">
                                        <div class="bg-gray-950/70 border border-gray-800 rounded-xl p-5 shadow 
                                                    group-hover:border-indigo-500 transition">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-semibold text-white">
                                                        {{ $comment->user->name ?? 'Anonymous' }}
                                                    </span>
                                                    <span class="text-xs text-gray-400">
                                                        • {{ date('d/m/Y H:i', $comment->created_at) }}
                                                    </span>
                                                </div>

                                                {{-- Acciones --}}
                                                <div class="flex gap-2">
                                                    <button class="text-gray-400 hover:text-indigo-400 transition text-xs font-medium">
                                                        Like
                                                    </button>
                                                    <button class="text-gray-400 hover:text-indigo-400 transition text-xs font-medium">
                                                        Reply
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- Contenido del comentario --}}
                                            <div class="text-gray-300 leading-relaxed comment-content">
                                                {!! $comment->comment !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-400">No comments yet. Be the first to comment!</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- Related News -->
                    <div class="bg-gray-900/50 rounded-2xl backdrop-blur-md shadow-lg border border-gray-800 p-6">
                        <h3 class="text-xl font-semibold text-white mb-6">Related News</h3>
                        <div class="space-y-6">
                            @foreach($item->relatedContents() as $related)
                                <div class="group">
                                    <a href="{{ route('news.show', $related->id) }}" class="block">
                                        <h4 class="text-white font-medium group-hover:text-indigo-400 transition">
                                            {{ $related->title }}
                                        </h4>
                                        @if($related->category)
                                            <span class="text-xs text-indigo-400 mt-1 block">
                                                {{ $related->category->name }}
                                            </span>
                                        @endif
                                        <p class="text-sm text-gray-400 mt-2">
                                            {{ Str::limit(strip_tags($related->content), 100) }}
                                        </p>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Newsletter -->
                    <div class="bg-gray-900/50 rounded-2xl backdrop-blur-md shadow-lg border border-gray-800 p-6">
                        <h3 class="text-xl font-semibold text-white mb-4">Stay Updated!</h3>        
                        <p class="text-gray-400 text-sm mb-4">Subscribe to receive the latest news and updates.</p>
                        
                        @if(session('success'))
                            <div class="text-sm text-green-400 mb-3">{{ session('success') }}</div>
                        @endif
                        
                        @if(session('error'))
                            <div class="text-sm text-red-400 mb-3">{{ session('error') }}</div>
                        @endif
                        
                        <form action="{{ route('subscribe') }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="email" name="email" required
                                class="w-full bg-gray-950/50 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-indigo-500"
                                placeholder="Your email address">
                            <button type="submit"
                                class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="mt-8">
                <a href="{{ route('news') }}"
                    class="inline-flex items-center text-indigo-400 hover:text-indigo-300 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                    </svg>
                    Back to News
                </a>
            </div>
        </div>
    </div>
@endsection
