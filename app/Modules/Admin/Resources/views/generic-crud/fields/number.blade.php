@php $value = old($field['name'], $record?->{$field['name']} ?? ($field['default'] ?? '')); @endphp
<div>
    <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $field['label'] ?? $field['name'] }}
        @if($field['required'] ?? false) <span class="text-red-500">*</span> @endif
    </label>
    <input type="number"
           id="{{ $field['name'] }}"
           name="{{ $field['name'] }}"
           value="{{ $value }}"
           step="{{ $field['step'] ?? 1 }}"
           min="{{ $field['min'] ?? '' }}"
           max="{{ $field['max'] ?? '' }}"
           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm
                  @error($field['name']) border-red-400 @enderror"
           {{ ($field['required'] ?? false) ? 'required' : '' }}>
    @if(!empty($field['hint']))
        <p class="mt-1 text-xs text-gray-400">{{ $field['hint'] }}</p>
    @endif
    @error($field['name'])
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
