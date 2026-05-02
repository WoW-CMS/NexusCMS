@extends('admin::layouts.app')

@section('title', 'Create Category')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.news.categories.index') }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Create Category</h1>
    </div>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.news.categories.store') }}" class="max-w-2xl space-y-6">
        @csrf
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800">Category Details</h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           id="name-input"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Category name">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" id="slug-input"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono"
                           placeholder="auto-generated-from-name">
                    <p class="text-xs text-gray-400 mt-1">Leave empty to auto-generate from name.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Optional category description">{{ old('description') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Display Order</label>
                        <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-end pb-2">
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.news.categories.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm font-medium">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                    <i class="fas fa-save mr-1"></i> Create Category
                </button>
            </div>
        </div>
    </form>
</main>

@push('scripts')
<script>
    const nameInput = document.getElementById('name-input');
    const slugInput = document.getElementById('slug-input');
    function slugify(t) {
        return t.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'')
            .replace(/\s+/g,'-').replace(/[^\w\-]+/g,'').replace(/--+/g,'-').replace(/^-+|-+$/g,'');
    }
    nameInput.addEventListener('input', function() {
        if (!slugInput.value) slugInput.placeholder = slugify(this.value) || 'auto-generated-from-name';
    });
</script>
@endpush
@endsection
