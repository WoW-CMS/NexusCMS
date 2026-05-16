{{--
    Richtext field: textarea enhanced with CKEditor 5.
    CKEditor must be included via the layout or @stack('scripts').
--}}
@php $value = old($field['name'], $record?->{$field['name']} ?? ($field['default'] ?? '')); @endphp
<div>
    <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $field['label'] ?? $field['name'] }}
        @if($field['required'] ?? false) <span class="text-red-500">*</span> @endif
    </label>
    <textarea id="{{ $field['name'] }}"
              name="{{ $field['name'] }}"
              class="richtext-editor w-full rounded-lg border-gray-300 text-sm
                     @error($field['name']) border-red-400 @enderror"
              {{ ($field['required'] ?? false) ? 'required' : '' }}>{{ $value }}</textarea>
    @if(!empty($field['hint']))
        <p class="mt-1 text-xs text-gray-400">{{ $field['hint'] }}</p>
    @endif
    @error($field['name'])
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
