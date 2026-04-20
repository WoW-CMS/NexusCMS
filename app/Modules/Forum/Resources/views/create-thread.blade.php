@extends('layouts.main')

@section('title', 'New Thread — ' . $forum->name)

@section('content')
<div class="pt-16 min-h-screen bg-slate-950">
    <div class="bg-slate-900 border-b border-slate-800/70">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6">
            <nav class="flex items-center gap-1.5 text-sm text-slate-500 mb-3">
                <a href="{{ route('home') }}" class="hover:text-slate-300"><i class="fas fa-home"></i></a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <a href="{{ route('forum.index') }}" class="hover:text-slate-300">Forums</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <a href="{{ route('forum.show', $forum->slug) }}" class="hover:text-slate-300">{{ $forum->name }}</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-slate-300">New Thread</span>
            </nav>
            <h1 class="text-xl font-bold text-white">Start a new thread</h1>
            <p class="text-slate-400 text-sm mt-0.5">Posting in <span class="text-slate-300 font-medium">{{ $forum->name }}</span></p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">
        @if($errors->any())
        <div class="mb-5 flex items-start gap-3 bg-red-900/30 border border-red-700/50 text-red-300 text-sm px-4 py-3 rounded-lg">
            <i class="fas fa-exclamation-circle text-red-400 mt-0.5 flex-shrink-0"></i>
            <ul class="space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="{{ route('forum.store-thread', $forum->slug) }}" method="POST"
              class="rounded-xl border border-slate-800 bg-slate-900 overflow-hidden shadow-xl">
            @csrf
            <div class="px-6 py-5 space-y-5">
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-300 mb-1.5">
                        Thread Title <span class="text-red-400">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="Enter a clear, descriptive title…"
                        maxlength="255"
                        class="w-full bg-slate-800/60 border {{ $errors->has('title') ? 'border-red-500' : 'border-slate-700' }} rounded-lg px-4 py-2.5 text-slate-100 text-sm placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50"
                        required>
                    @error('title')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="content" class="block text-sm font-medium text-slate-300 mb-1.5">
                        Content <span class="text-red-400">*</span>
                    </label>
                    <textarea
                        id="content"
                        name="content"
                        rows="12"
                        placeholder="Write the body of your thread here…"
                        class="w-full bg-slate-800/60 border {{ $errors->has('content') ? 'border-red-500' : 'border-slate-700' }} rounded-lg px-4 py-3 text-slate-100 text-sm placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 resize-y"
                        required minlength="10">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex items-center justify-between gap-3 px-6 py-4 bg-slate-900/80 border-t border-slate-800">
                <a href="{{ route('forum.show', $forum->slug) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-slate-400 hover:text-slate-200 text-sm">
                    <i class="fas fa-arrow-left text-xs"></i> Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg shadow-lg shadow-indigo-900/30">
                    <i class="fas fa-paper-plane text-xs"></i> Post Thread
                </button>
            </div>
        </form>
        <div class="mt-5 rounded-xl border border-slate-800 bg-slate-900/40 px-5 py-4">
            <h3 class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-3">
                <i class="fas fa-shield-alt mr-1.5 text-slate-600"></i> Posting Guidelines
            </h3>
            <ul class="space-y-1.5 text-slate-500 text-xs">
                <li class="flex items-start gap-2"><i class="fas fa-check text-emerald-500/70 mt-0.5 flex-shrink-0"></i> Be respectful and constructive.</li>
                <li class="flex items-start gap-2"><i class="fas fa-check text-emerald-500/70 mt-0.5 flex-shrink-0"></i> Search before posting to avoid duplicates.</li>
                <li class="flex items-start gap-2"><i class="fas fa-check text-emerald-500/70 mt-0.5 flex-shrink-0"></i> Use a clear, descriptive title.</li>
                <li class="flex items-start gap-2"><i class="fas fa-times text-red-500/70 mt-0.5 flex-shrink-0"></i> Do not share personal information or violate privacy.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
