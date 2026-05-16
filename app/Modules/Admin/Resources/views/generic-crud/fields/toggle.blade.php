@php $checked = (bool) old($field['name'], $record?->{$field['name']} ?? ($field['default'] ?? false)); @endphp
<div>
    <label class="flex items-center gap-3 cursor-pointer">
        <input type="hidden" name="{{ $field['name'] }}" value="0">
        <input type="checkbox"
               id="{{ $field['name'] }}"
               name="{{ $field['name'] }}"
               value="1"
               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4"
               {{ $checked ? 'checked' : '' }}>
        <span class="text-sm font-medium text-gray-700">
            {{ $field['label'] ?? $field['name'] }}
        </span>
    </label>
    @if(!empty($field['hint']))
        <p class="mt-1 ml-7 text-xs text-gray-400">{{ $field['hint'] }}</p>
    @endif
    @error($field['name'])
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
