@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-900 to-gray-950 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Alerts -->
        @if (session('success'))
            <div class="bg-emerald-900/20 border-l-4 border-emerald-400 backdrop-blur-sm p-4 mb-8 rounded-r-lg">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-emerald-200">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-gray-900/40 rounded-2xl overflow-hidden backdrop-blur-sm shadow-xl shadow-black/20 border border-gray-800/10">
                    <!-- Featured Image with Enhanced Overlay -->
                    @if($item->featured_image)
                        <div class="relative h-96 overflow-hidden group">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-900/50 to-transparent z-10 
                                      transition-opacity duration-300 group-hover:opacity-75"></div>
                            <img class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105" 
                                 src="{{ asset('storage/' . $item->featured_image) }}" 
                                 alt="{{ $item->title }}">
                        </div>
                    @endif

                    <!-- Article Content -->
                    <div class="p-8">
                        <div class="flex flex-wrap items-center gap-4 mb-6">
                            <span class="px-4 py-1.5 bg-indigo-500/10 text-indigo-300 rounded-full text-sm font-medium backdrop-blur-sm">
                                News
                            </span>
                            <span class="text-sm text-gray-400 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $item->published_at ? $item->published_at->format('M d, Y') : 'Unpublished' }}
                            </span>
                            <span class="text-sm text-gray-400 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                5 min read
                            </span>
                        </div>

                        <h1 class="text-4xl font-bold text-white mb-6 leading-tight">{{ $item->title }}</h1>

                        <!-- Enhanced Author Information -->
                        <div class="flex items-center mb-8 border-b border-gray-800/50 pb-6">
                            <div class="relative group">
                                <img class="h-14 w-14 rounded-full object-cover ring-2 ring-indigo-500/30 group-hover:ring-indigo-400/50" 
                                     src="{{ $item->author->avatar ?? asset('images/default-avatar.png') }}" 
                                     alt="Author">
                                <div class="absolute -bottom-1 -right-1 bg-emerald-400 h-4 w-4 rounded-full border-2 border-gray-900"></div>
                            </div>
                            <div class="ml-4">
                                <p class="text-white font-medium group-hover:text-blue-400 transition-colors">
                                    {{ $item->author->name ?? 'Anonymous' }}
                                </p>
                                <p class="text-sm text-gray-400">{{ $item->author->role ?? 'Editor' }}</p>
                                <p class="text-xs text-gray-500 mt-1">Member since {{ $item->author->created_at ? $item->author->created_at->format('M Y') : 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Enhanced Content Styles -->
                        <div class="prose prose-lg prose-invert max-w-none prose-headings:text-indigo-300 prose-a:text-indigo-400 hover:prose-a:text-indigo-300">
                            {!! $item->content !!}
                        </div>

                        <!-- Tags -->
                        <div class="mt-8 flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-indigo-500/10 text-indigo-300 rounded-full text-sm hover:bg-indigo-500/20 transition-colors cursor-pointer">
                                #WorldOfWarcraft
                            </span>
                            <span class="px-3 py-1 bg-indigo-500/10 text-indigo-300 rounded-full text-sm hover:bg-indigo-500/20 transition-colors cursor-pointer">
                                #Gaming
                            </span>
                            <span class="px-3 py-1 bg-indigo-500/10 text-indigo-300 rounded-full text-sm hover:bg-indigo-500/20 transition-colors cursor-pointer">
                                #MMORPG
                            </span>
                        </div>

                        <!-- Share -->
                        <div class="mt-8 flex items-center gap-4">
                            <span class="text-sm text-gray-400">Share:</span>
                            <div class="flex gap-3">
                                <a href="#" class="text-gray-400 hover:text-indigo-400 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="mt-8 bg-gray-900/40 rounded-2xl backdrop-blur-sm shadow-xl shadow-black/20 border border-gray-800/10 p-8">
                    <h3 class="text-xl font-semibold text-white mb-6">Comments</h3>
                    
                    <!-- Comment Form -->
                    <div class="mb-8">
                        <div class="flex gap-4">
                            <img class="h-10 w-10 rounded-full ring-2 ring-indigo-500/20" src="{{ asset('images/default-avatar.png') }}" alt="Your avatar">
                            <div class="flex-1">
                                <textarea 
                                    class="w-full bg-gray-950/50 border-0 rounded-lg p-4 text-gray-300 focus:ring-2 focus:ring-indigo-500/30 focus:border-transparent"
                                    rows="3"
                                    placeholder="Write a comment..."></textarea>
                                <button class="mt-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                    Comment
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Comments List -->
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <img class="h-10 w-10 rounded-full ring-2 ring-indigo-500/20" src="{{ asset('images/default-avatar.png') }}" alt="Avatar">
                            <div>
                                <div class="bg-gray-950/50 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-medium text-white">Example User</span>
                                        <span class="text-sm text-gray-400">2 hours ago</span>
                                    </div>
                                    <p class="text-gray-300">Amazing article! Can't wait to try this new raid.</p>
                                </div>
                                <div class="mt-2 flex gap-4 text-sm">
                                    <button class="text-gray-400 hover:text-indigo-400 transition-colors">Like</button>
                                    <button class="text-gray-400 hover:text-indigo-400 transition-colors">Reply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Related News -->
                <div class="bg-gray-900/40 rounded-2xl backdrop-blur-sm shadow-xl shadow-black/20 border border-gray-800/10 p-6">
                    <h3 class="text-xl font-semibold text-white mb-6">Related News</h3>
                    <div class="space-y-6">
                        <!-- Related News 1 -->
                        <div class="group">
                            <div class="relative h-40 rounded-lg overflow-hidden mb-3">
                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/50 to-transparent z-10"></div>
                                <img class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110" 
                                     src="{{ asset('images/placeholder.jpg') }}" alt="Related news">
                            </div>
                            <h4 class="text-white font-medium group-hover:text-indigo-400 transition-colors">New PvP Season</h4>
                            <p class="text-sm text-gray-400 mt-2">Get ready for the new PvP season with amazing rewards...</p>
                        </div>

                        <!-- Related News 2 -->
                        <div class="group">
                            <div class="relative h-40 rounded-lg overflow-hidden mb-3">
                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/50 to-transparent z-10"></div>
                                <img class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110" 
                                     src="{{ asset('images/placeholder.jpg') }}" alt="Related news">
                            </div>
                            <h4 class="text-white font-medium group-hover:text-indigo-400 transition-colors">New Raid Guide</h4>
                            <p class="text-sm text-gray-400 mt-2">Everything you need to know about the new mechanics...</p>
                        </div>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="bg-gray-900/40 rounded-2xl backdrop-blur-sm shadow-xl shadow-black/20 border border-gray-800/10 p-6">
                    <h3 class="text-xl font-semibold text-white mb-4">Stay Informed!</h3>
                    <p class="text-gray-400 text-sm mb-4">Subscribe to receive the latest news and updates.</p>
                    <form class="space-y-3">
                        <input type="email" 
                               class="w-full bg-gray-950/50 border-0 rounded-lg px-4 py-2 text-gray-300 focus:ring-2 focus:ring-indigo-500/30 focus:border-transparent"
                               placeholder="Your email">
                        <button class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="mt-8">
            <a href="{{ route('news') }}" 
               class="inline-flex items-center text-indigo-400 hover:text-indigo-300 transition-colors duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                </svg>
                Back to News
            </a>
        </div>
    </div>
</div>
@endsection