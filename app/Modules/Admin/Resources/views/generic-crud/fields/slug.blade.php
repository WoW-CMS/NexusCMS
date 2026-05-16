{{--
    Slug field: renders as a text input with optional JS auto-generation from a source field.
--}}
@php $value = old($field['name'], $record?->{$field['name']} ?? ''); @endphp
<div>
    <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $field['label'] ?? $field['name'] }}
        @if($field['required'] ?? false) <span class="text-red-500">*</span> @endif
    </label>
    <input type="text"
           id="{{ $field['name'] }}"
           name="{{ $field['name'] }}"
           value="{{ $value }}"
           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm font-mono
                  @error($field['name']) border-red-400 @enderror"
           {{ ($field['required'] ?? false) ? 'required' : '' }}>
    @if(!empty($field['hint']))
        <p class="mt-1 text-xs text-gray-400">{{ $field['hint'] }}</p>
    @endif
    @error($field['name'])
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

@if(!empty($field['source']))
@push('scripts')
<script>
(function () {
    const src = document.getElementById('{{ $field['source'] }}');
    const slug = document.getElementById('{{ $field['name'] }}');
    if (!src || !slug) return;

    src.addEventListener('input', function () {
        if (slug.dataset.edited) return;
        slug.value = src.value
            .toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/[\s]+/g, '-');
    });

    slug.addEventListener('input', function () {
        slug.dataset.edited = '1';
    });
})();
</script>
@endpush
@endif
