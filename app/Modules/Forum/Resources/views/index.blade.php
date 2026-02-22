@extends('layouts.main')

@section('title', 'Forums')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Header Section --}}
        <div class="mb-12">
            <div class="flex items-center space-x-2 text-sm text-gray-400 mb-6">
                <a href="{{ route('home') }}" class="hover:text-blue-400 transition-colors duration-200 flex items-center">
                    <i class="fas fa-home mr-1"></i>
                    <span>Home</span>
                </a>
                <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="text-gray-300">Forums</span>
            </div>

            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-900/40 via-gray-800/60 to-gray-900/40 backdrop-blur-sm border border-blue-500/20 shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-purple-600/5 animate-pulse"></div>
                <div class="relative px-8 py-10">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-start space-x-6">
                            <div class="relative">
                                <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full blur opacity-30"></div>
                                <div class="relative bg-gray-800 p-4 rounded-full border-2 border-blue-400/30">
                                    <i class="fas fa-comments text-3xl text-blue-400"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h1 class="text-5xl font-bold text-white mb-3 bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                                    Community Forums
                                </h1>
                                <p class="text-gray-300 text-lg leading-relaxed max-w-2xl">
                                    Connect, share, and learn with thousands of passionate members in our vibrant community.
                                </p>
                            </div>
                        </div>
                        <div class="mt-8 lg:mt-0">
                            <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50 shadow-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="relative">
                                        <div class="absolute inset-0 bg-green-500 rounded-full animate-ping opacity-75"></div>
                                        <div class="relative bg-green-500 w-3 h-3 rounded-full"></div>
                                    </div>
                                    <div class="text-gray-300">
                                        <span class="text-green-400 font-bold text-xl">{{ App\Models\User::where('updated_at', '>=', now()->subMinutes(15))->count() }}</span>
                                        <span class="text-sm ml-1">members online</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Forum Categories --}}
        <div class="space-y-8">
            @foreach($categories as $category)
            <div class="group">
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-r from-blue-900/60 to-purple-900/40 backdrop-blur-sm border border-blue-500/30 shadow-lg">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-purple-600/10 group-hover:from-blue-600/20 group-hover:to-purple-600/20 transition-all duration-500"></div>
                    <div class="relative px-8 py-6">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="absolute -inset-1 bg-gradient-to-r from-blue-400 to-purple-400 rounded-lg blur opacity-25 group-hover:opacity-40 transition-opacity duration-300"></div>
                                <div class="relative bg-gray-800 p-3 rounded-lg border border-blue-400/30">
                                    <i class="fas fa-folder-open text-2xl text-blue-400"></i>
                                </div>
                            </div>
                            <h2 class="text-2xl font-bold text-white group-hover:text-blue-300 transition-colors duration-300">
                                {{ $category->name }}
                            </h2>
                            <div class="ml-auto">
                                <span class="bg-blue-500/20 text-blue-300 px-4 py-2 rounded-full text-sm font-medium border border-blue-500/30">
                                    {{ $category->subforums->count() }} {{ Str::plural('forum', $category->subforums->count()) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800/40 backdrop-blur-sm rounded-b-2xl border border-t-0 border-gray-700/50 shadow-xl">
                    <div class="divide-y divide-gray-700/30">
                        @foreach($category->subforums as $forum)
                        <div class="group/forum hover:bg-gray-800/60 transition-all duration-300 first:rounded-t-none last:rounded-b-xl">
                            <div class="p-8">
                                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between space-y-6 xl:space-y-0">
                                    <div class="flex items-start space-x-6 flex-1">
                                        <div class="relative flex-shrink-0">
                                            <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl blur opacity-20 group-hover/forum:opacity-40 transition-opacity duration-300"></div>
                                            <div class="relative bg-gray-700 p-4 rounded-xl border border-gray-600/50 group-hover/forum:border-blue-500/50 transition-colors duration-300">
                                                <i class="fas fa-comments text-3xl text-blue-400 group-hover/forum:text-blue-300 transition-colors duration-300"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-3">
                                                <a href="{{ route('forum.show', $forum->slug) }}" 
                                                   class="text-2xl font-bold text-white hover:text-blue-400 transition-all duration-300 hover:translate-x-1 transform">
                                                    {{ $forum->name }}
                                                </a>
                                                <span class="bg-gray-700 text-gray-300 px-3 py-1 rounded-full text-xs font-medium">
                                                    {{ $forum->threads()->count() }} {{ Str::plural('thread', $forum->threads()->count()) }}
                                                </span>
                                            </div>
                                            <p class="text-gray-400 leading-relaxed text-sm">
                                                {{ $forum->description }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="xl:ml-8 xl:text-right">
                                        @if($forum->latest_thread)
                                        <div class="bg-gray-900/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50 shadow-lg hover:shadow-xl transition-all duration-300">
                                            <div class="flex items-start space-x-4 xl:flex-col xl:items-end xl:space-x-0 xl:space-y-2">
                                                <div class="flex-1 xl:w-full">
                                                    <div class="text-xs text-gray-500 mb-2">Latest Thread</div>
                                                    <a href="{{ route('forums.thread', ['forumSlug' => $forum->slug, 'threadSlug' => $forum->latestThread->slug]) }}" 
                                                       class="text-blue-400 hover:text-blue-300 font-semibold transition-colors duration-200 block">
                                                        {{ Str::limit($forum->latestThread->title, 40) }}
                                                    </a>
                                                </div>
                                                <div class="text-sm text-gray-400">
                                                    <div class="flex items-center space-x-2 xl:justify-end">
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($forum->latestThread->user->name) }}&background=3B82F6&color=fff&size=32" 
                                                             alt="{{ $forum->latestThread->user->name }}" 
                                                             class="w-6 h-6 rounded-full">
                                                        <span>by <span class="font-medium text-gray-300">{{ $forum->latestThread->user->name }}</span></span>
                                                    </div>
                                                    <div class="text-xs mt-1 xl:text-right">
                                                        {{ $forum->latestThread->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="bg-gray-900/30 rounded-xl p-6 border border-dashed border-gray-700 text-center">
                                            <i class="fas fa-comment-slash text-3xl text-gray-600 mb-3"></i>
                                            <p class="text-gray-500 text-sm">No threads yet</p>
                                            <p class="text-xs text-gray-600 mt-1">Be the first to start a discussion!</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Statistics Section --}}
        <div class="mt-16">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-gray-800/60 via-gray-900/40 to-blue-900/30 backdrop-blur-sm border border-gray-700/50 shadow-2xl">
                <div class="absolute inset-0">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-purple-600/5"></div>
                    <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/10 rounded-full filter blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-500/10 rounded-full filter blur-3xl"></div>
                </div>
                
                <div class="relative px-8 py-12">
                    <div class="text-center mb-12">
                        <div class="inline-flex items-center space-x-4 mb-4">
                            <div class="relative">
                                <div class="absolute -inset-1 bg-gradient-to-r from-blue-400 to-purple-400 rounded-lg blur opacity-30"></div>
                                <div class="relative bg-gray-800 p-4 rounded-lg border border-blue-400/30">
                                    <i class="fas fa-chart-line text-3xl text-blue-400"></i>
                                </div>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-white">Community Statistics</h2>
                                <p class="text-gray-400 mt-2">Our growing family by the numbers</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="group/stat">
                            <div class="relative overflow-hidden rounded-xl bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 p-8 text-center hover:border-blue-500/50 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/10">
                                <div class="absolute -top-8 -right-8 w-24 h-24 bg-blue-500/10 rounded-full filter blur-2xl group-hover/stat:bg-blue-500/20 transition-colors duration-300"></div>
                                <div class="relative">
                                    <div class="text-5xl font-bold text-transparent bg-gradient-to-r from-blue-400 to-blue-600 bg-clip-text mb-2">
                                        {{ number_format(App\Models\User::count()) }}
                                    </div>
                                    <div class="flex items-center justify-center space-x-2 text-gray-300">
                                        <i class="fas fa-users text-blue-400"></i>
                                        <span class="font-medium">Registered Members</span>
                                    </div>
                                    <div class="mt-4 text-xs text-gray-500">
                                        <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                                        Growing every day
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="group/stat">
                            <div class="relative overflow-hidden rounded-xl bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 p-8 text-center hover:border-green-500/50 transition-all duration-300 hover:shadow-xl hover:shadow-green-500/10">
                                <div class="absolute -top-8 -right-8 w-24 h-24 bg-green-500/10 rounded-full filter blur-2xl group-hover/stat:bg-green-500/20 transition-colors duration-300"></div>
                                <div class="relative">
                                    <div class="text-5xl font-bold text-transparent bg-gradient-to-r from-green-400 to-green-600 bg-clip-text mb-2">
                                        {{ number_format(App\Models\Thread::count()) }}
                                    </div>
                                    <div class="flex items-center justify-center space-x-2 text-gray-300">
                                        <i class="fas fa-comments text-green-400"></i>
                                        <span class="font-medium">Active Threads</span>
                                    </div>
                                    <div class="mt-4 text-xs text-gray-500">
                                        <i class="fas fa-fire text-orange-400 mr-1"></i>
                                        Full of discussions
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="group/stat">
                            <div class="relative overflow-hidden rounded-xl bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 p-8 text-center hover:border-purple-500/50 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/10">
                                <div class="absolute -top-8 -right-8 w-24 h-24 bg-purple-500/10 rounded-full filter blur-2xl group-hover/stat:bg-purple-500/20 transition-colors duration-300"></div>
                                <div class="relative">
                                    <div class="text-5xl font-bold text-transparent bg-gradient-to-r from-purple-400 to-purple-600 bg-clip-text mb-2">
                                        {{ number_format(App\Models\Post::count()) }}
                                    </div>
                                    <div class="flex items-center justify-center space-x-2 text-gray-300">
                                        <i class="fas fa-comment-dots text-purple-400"></i>
                                        <span class="font-medium">Total Posts</span>
                                    </div>
                                    <div class="mt-4 text-xs text-gray-500">
                                        <i class="fas fa-heart text-red-400 mr-1"></i>
                                        Community contributions
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection