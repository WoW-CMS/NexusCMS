<div id="features" class="bg-slate-800/50 backdrop-blur-sm rounded-2xl overflow-hidden border border-slate-700/50 hover:border-blue-500/50 transition-all duration-300 group">
    <div class="relative">
        <img src="{{ asset('storage/images/' . $news->image) }}" alt="{{ $news->title }}" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
        <div class="absolute top-4 left-4">
            <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Featured</span>
        </div>
    </div>
    <div class="p-6 space-y-3">
        <div class="text-sm text-slate-400">{{ $news->published_at ? $news->published_at->format('F d, Y') : 'Draft' }}</div>
        <h3 class="text-2xl font-bold text-white group-hover:text-blue-400 transition-colors duration-200">{{ $news->title }}</h3>
        <p class="text-slate-300 leading-relaxed">{{ Str::limit($news->content, 200) }}</p>
        <button class="text-blue-400 hover:text-blue-300 font-medium inline-flex items-center gap-2 group/btn mt-2">
            Read More 
            <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>
</div>