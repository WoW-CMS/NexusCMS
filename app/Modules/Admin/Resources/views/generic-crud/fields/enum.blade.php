@php
    $value = old($field['name'], $record?->{$field['name']} ?? ($field['default'] ?? ''));
    $options = $field['options'] ?? [];
    // Support both flat arrays ["val1","val2"] and label/value pairs [{"value":"v","label":"L"}]
    $normalized = [];
    foreach ($options as $opt) {
        if (is_array($opt)) {
            $normalized[] = $opt;
        } else {
            $normalized[] = ['value' => $opt, 'label' => $opt];
        }
    }
@endphp
<div>
    <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700 mb-1.5">
        {{ $field['label'] ?? $field['name'] }}
        @if($field['required'] ?? false) <span class="text-red-500 ml-0.5">*</span> @endif
    </label>
    <div class="relative">
        <select id="{{ $field['name'] }}"
                name="{{ $field['name'] }}"
                class="w-full appearance-none rounded-lg border text-sm px-3 py-2 pr-8
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                       @error($field['name']) border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror"
                {{ ($field['required'] ?? false) ? 'required' : '' }}>
            @if(!($field['required'] ?? false))
                <option value="">— Select —</option>
            @endif
            @foreach($normalized as $option)
                <option value="{{ $option['value'] }}"
                        {{ (string) $value === (string) $option['value'] ? 'selected' : '' }}>
                    {{ $option['label'] }}
                </option>
            @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-gray-400">
            <i class="fas fa-chevron-down text-[10px]"></i>
        </div>
    </div>
    @if(!empty($field['hint']))
        <p class="mt-1.5 text-xs text-gray-400">{{ $field['hint'] }}</p>
    @endif
    @error($field['name'])
        <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
            <i class="fas fa-circle-exclamation text-[10px]"></i>{{ $message }}
        </p>
    @enderror
</div>
