@if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $plan?->name) }}" required
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea name="description" rows="2"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $plan?->description) }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Amount (USD) <span class="text-red-500">*</span></label>
            <input type="number" name="amount" value="{{ old('amount', $plan?->amount) }}" step="0.01" min="0.01" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">DP Base <span class="text-red-500">*</span></label>
            <input type="number" name="dp_base" value="{{ old('dp_base', $plan?->dp_base) }}" min="1" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Bonus % (extra DP)</label>
            <input type="number" name="extra_pct" value="{{ old('extra_pct', $plan?->extra_pct ?? 0) }}" min="0"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $plan?->sort_order ?? 0) }}" min="0"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
    </div>

    <div class="flex items-center gap-6 pt-1">
        <div class="flex items-center gap-2">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" id="active" value="1"
                   @checked(old('active', $plan?->active ?? true))
                   class="h-4 w-4 text-blue-600 border-gray-300 rounded">
            <label for="active" class="text-sm font-medium text-gray-700">Active</label>
        </div>
        <div class="flex items-center gap-2">
            <input type="hidden" name="is_promo" value="0">
            <input type="checkbox" name="is_promo" id="is_promo" value="1"
                   @checked(old('is_promo', $plan?->is_promo))
                   class="h-4 w-4 text-yellow-500 border-gray-300 rounded">
            <label for="is_promo" class="text-sm font-medium text-gray-700">Promo plan</label>
        </div>
    </div>
</div>
