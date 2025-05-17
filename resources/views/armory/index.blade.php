@extends('layouts.main')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-950 to-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="relative text-center mb-16">
                <div class="absolute inset-0 flex items-center justify-center opacity-5">
                    <div class="w-96 h-96 bg-blue-500 rounded-full filter blur-3xl"></div>
                </div>
                <div class="relative">
                    <h2
                        class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 sm:text-6xl mb-4">
                        WoW Armory
                    </h2>
                    <p class="mt-4 text-xl text-gray-400 max-w-3xl mx-auto">
                        Search for World of Warcraft characters
                    </p>
                </div>
            </div>

            <!-- Search Bar -->
            <form action="{{ route('armory') }}" method="GET" class="mb-12">
                <div class="flex justify-center">
                    <div class="relative w-full max-w-xl">
                        @php
                            $search = request('q');
                        @endphp
                        <input name="q" type="text" placeholder="Search by player name"
                            value="{{ old('q', $search) }}"
                            class="w-full bg-gray-800 bg-opacity-50 placeholder-gray-500 text-gray-200 rounded-full py-3 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 12.65z" />
                            </svg>
                        </div>
                    </div>
                    <button type="submit"
                        class="ml-4 inline-block px-6 py-3 bg-indigo-600 text-white rounded-full hover:bg-indigo-500 transition">
                        Search
                    </button>
                </div>
            </form>

            <!-- Results Grid -->
            @if (filled($search))
                <section>
                    @if ($data->count())
                        <h2 class="text-2xl font-semibold text-white mb-6">Players</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
                            @foreach ($data as $player)
                                <div
                                    class="group bg-gray-900/50 rounded-2xl backdrop-blur-sm shadow-lg border border-gray-800 p-6 flex flex-col">
                                    <div class="flex items-center space-x-4 mb-4">
                                        <img src="{{ $player->avatar_url }}" alt="Avatar"
                                            class="h-16 w-16 rounded-full object-cover">
                                        <h3 class="text-2xl font-bold text-white">{{ $player->name }}</h3>
                                    </div>
                                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 mb-6 text-gray-200">
                                        <div>Level <span class="font-semibold text-white">{{ $player->level }}</span></div>
                                        <div>Class <span class="font-semibold"
                                                style="color: {{ $player->class->color() }}">{{ $player->class->label() }}</span>
                                        </div>
                                        <div>Race <span class="font-semibold text-white">{{ $player->race->label() }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('armory.show', $player->getKey()) }}"
                                        class="mt-auto inline-block text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 transition">
                                        View Character
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="mt-12">
                                <div class="flex justify-center">
                                    @if ($data->hasPages())
                                        <nav role="navigation" aria-label="Pagination Navigation"
                                            class="flex items-center gap-1">
                                            {{-- Previous Page Link --}}
                                            @if ($data->onFirstPage())
                                                <span
                                                    class="px-4 py-2 text-sm font-medium text-gray-500 bg-gray-800/40 rounded-lg cursor-not-allowed">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @else
                                                <a href="{{ $data->previousPageUrl() }}" rel="prev"
                                                    class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-800/40 rounded-lg hover:bg-indigo-600/20 hover:text-indigo-400 transition-colors">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            @endif

                                            {{-- Custom Pagination Elements --}}
                                            @php
                                                $currentPage = $data->currentPage();
                                                $lastPage = $data->lastPage();
                                                $start = max(1, $currentPage - 2);
                                                $end = min($lastPage, $currentPage + 2);
                                            @endphp

                                            @if ($start > 1)
                                                <a href="{{ $data->url(1) }}"
                                                    class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-800/40 rounded-lg hover:bg-indigo-600/20 hover:text-indigo-400 transition-colors">
                                                    1
                                                </a>
                                                @if ($start > 2)
                                                    <span class="px-2 text-gray-400">...</span>
                                                @endif
                                            @endif

                                            @for ($page = $start; $page <= $end; $page++)
                                                @if ($page == $currentPage)
                                                    <span
                                                        class="px-4 py-2 text-sm font-medium text-indigo-400 bg-indigo-600/20 rounded-lg">{{ $page }}</span>
                                                @else
                                                    <a href="{{ $data->url($page) }}"
                                                        class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-800/40 rounded-lg hover:bg-indigo-600/20 hover:text-indigo-400 transition-colors">
                                                        {{ $page }}
                                                    </a>
                                                @endif
                                            @endfor

                                            @if ($end < $lastPage)
                                                @if ($end < $lastPage - 1)
                                                    <span class="px-2 text-gray-400">...</span>
                                                @endif
                                                <a href="{{ $data->url($lastPage) }}"
                                                    class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-800/40 rounded-lg hover:bg-indigo-600/20 hover:text-indigo-400 transition-colors">
                                                    {{ $lastPage }}
                                                </a>
                                            @endif

                                            {{-- Next Page Link --}}
                                            @if ($data->hasMorePages())
                                                <a href="{{ $data->nextPageUrl() }}" rel="next"
                                                    class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-800/40 rounded-lg hover:bg-indigo-600/20 hover:text-indigo-400 transition-colors">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            @else
                                                <span
                                                    class="px-4 py-2 text-sm font-medium text-gray-500 bg-gray-800/40 rounded-lg cursor-not-allowed">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </nav>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @else
                        <p class="text-center text-gray-400">
                            No results found for
                            “<strong class="text-white">{{ $search }}</strong>”.
                        </p>
                    @endif
                </section>
            @endif
        </div>
    </div>
@endsection
