@php $isEdit = isset($forum) && $forum !== null; @endphp

@if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $forum?->name) }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-red-500">*</span></label>
        <input type="text" name="slug" value="{{ old('slug', $forum?->slug) }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea name="description" rows="3"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $forum?->description) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
        <select name="parent_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="">— None (top level) —</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('parent_id', $forum?->parent_id) == $cat->id)>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
        <input type="number" name="order" value="{{ old('order', $forum?->order ?? 0) }}" min="0"
               class="w-32 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="is_category" value="0">
        <input type="checkbox" name="is_category" id="is_category" value="1"
               @checked(old('is_category', $forum?->is_category))
               class="h-4 w-4 text-blue-600 border-gray-300 rounded">
        <label for="is_category" class="text-sm font-medium text-gray-700">This is a category (container, no threads)</label>
    </div>
</div>
