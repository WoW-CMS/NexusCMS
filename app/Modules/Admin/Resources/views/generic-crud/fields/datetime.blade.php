@php $value = old($field['name'], $record?->{$field['name']} ? \Carbon\Carbon::parse($record->{$field['name']})->format('Y-m-d\TH:i') : ($field['default'] ?? '')); @endphp
<div>
    <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $field['label'] ?? $field['name'] }}
        @if($field['required'] ?? false) <span class="text-red-500">*</span> @endif
    </label>
    <input type="datetime-local"
           id="{{ $field['name'] }}"
           name="{{ $field['name'] }}"
           value="{{ $value }}"
           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm
                  @error($field['name']) border-red-400 @enderror"
           {{ ($field['required'] ?? false) ? 'required' : '' }}>
    @error($field['name'])
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
