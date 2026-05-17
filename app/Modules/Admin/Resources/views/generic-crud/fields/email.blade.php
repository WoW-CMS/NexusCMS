@php $value = old($field['name'], $record?->{$field['name']} ?? ($field['default'] ?? '')); @endphp
<div>
    <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700 mb-1.5">
        {{ $field['label'] ?? $field['name'] }}
        @if($field['required'] ?? false) <span class="text-red-500 ml-0.5">*</span> @endif
    </label>
    <input type="email"
           id="{{ $field['name'] }}"
           name="{{ $field['name'] }}"
           value="{{ $value }}"
           placeholder="{{ $field['placeholder'] ?? '' }}"
           @if(!empty($field['max'])) maxlength="{{ $field['max'] }}" @endif
           class="w-full rounded-lg border text-sm px-3 py-2
                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                  @error($field['name']) border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror"
           {{ ($field['required'] ?? false) ? 'required' : '' }}>
    @if(!empty($field['hint']))
        <p class="mt-1.5 text-xs text-gray-400">{{ $field['hint'] }}</p>
    @endif
    @error($field['name'])
        <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
            <i class="fas fa-circle-exclamation text-[10px]"></i>{{ $message }}
        </p>
    @enderror
</div>
