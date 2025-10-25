@foreach($news as $item)
<div class="bg-slate-800/30 backdrop-blur-sm rounded-xl p-5 border border-slate-700/50 hover:border-slate-600 transition-all duration-300 group cursor-pointer">
    <div class="text-xs text-slate-400 mb-2">{{ $item->published_at->format('F d, Y') }}</div>
    <h4 class="text-lg font-bold text-white mb-2 group-hover:text-blue-400 transition-colors">{{ $item->title }}</h4>
    <p class="text-slate-400 text-sm">{{ Str::limit($item->content, 100) }}</p>
</div>
@endforeach