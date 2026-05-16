@php $current = $record?->{$field['name']} ?? null; @endphp
<div>
    <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $field['label'] ?? $field['name'] }}
        @if($field['required'] ?? false) <span class="text-red-500">*</span> @endif
    </label>
    @if($current)
        <p class="mb-2 text-xs text-gray-500">
            Current: <span class="font-mono text-gray-700">{{ $current }}</span>
        </p>
    @endif
    <input type="file"
           id="{{ $field['name'] }}"
           name="{{ $field['name'] }}"
           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                  file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200
                  @error($field['name']) border border-red-400 rounded-lg p-1 @enderror"
           {{ ($field['required'] ?? false) && !$current ? 'required' : '' }}>
    @if(!empty($field['hint']))
        <p class="mt-1 text-xs text-gray-400">{{ $field['hint'] }}</p>
    @endif
    @error($field['name'])
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
