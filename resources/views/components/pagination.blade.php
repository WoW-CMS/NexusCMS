@if ($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator && $paginator->hasPages())
    <div class="mt-12">
        <div class="flex justify-center">
            <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-4">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-500 bg-gray-800/40 rounded-xl cursor-not-allowed transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Previous
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" 
                       class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-400 bg-gray-800/40 rounded-xl hover:bg-indigo-600/20 hover:text-indigo-400 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Previous
                    </a>
                @endif

                <div class="flex items-center gap-3">
                    <div class="px-5 py-2.5 text-sm font-medium text-indigo-400 bg-indigo-600/20 rounded-xl border border-indigo-500/20">
                        Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
                    </div>
                </div>

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" 
                       class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-400 bg-gray-800/40 rounded-xl hover:bg-indigo-600/20 hover:text-indigo-400 transition-all duration-300">
                        Next
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @else
                    <span class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-500 bg-gray-800/40 rounded-xl cursor-not-allowed transition-all duration-300">
                        Next
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                @endif
            </nav>
        </div>
    </div>
@endif