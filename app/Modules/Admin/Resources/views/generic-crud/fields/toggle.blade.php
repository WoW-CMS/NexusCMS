@php $checked = (bool) old($field['name'], $record?->{$field['name']} ?? ($field['default'] ?? false)); @endphp
<div class="flex items-start gap-4 py-1">
    {{-- Toggle switch --}}
    <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-0.5">
        <input type="hidden" name="{{ $field['name'] }}" value="0">
        <input type="checkbox"
               id="{{ $field['name'] }}"
               name="{{ $field['name'] }}"
               value="1"
               class="sr-only peer"
               {{ $checked ? 'checked' : '' }}>
        <div class="w-10 h-5 bg-gray-200 rounded-full peer-focus:ring-2 peer-focus:ring-blue-400
                    peer-checked:bg-blue-600 transition-colors duration-200 relative
                    after:content-[''] after:absolute after:top-0.5 after:left-0.5
                    after:w-4 after:h-4 after:bg-white after:rounded-full after:shadow
                    after:transition-transform after:duration-200
                    peer-checked:after:translate-x-5"></div>
    </label>
    <div class="flex-1 min-w-0">
        <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700 cursor-pointer leading-5">
            {{ $field['label'] ?? $field['name'] }}
            @if($field['required'] ?? false) <span class="text-red-500 ml-0.5">*</span> @endif
        </label>
        @if(!empty($field['hint']))
            <p class="mt-0.5 text-xs text-gray-400">{{ $field['hint'] }}</p>
        @endif
        @error($field['name'])
            <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                <i class="fas fa-circle-exclamation text-[10px]"></i>{{ $message }}
            </p>
        @enderror
    </div>
</div>
