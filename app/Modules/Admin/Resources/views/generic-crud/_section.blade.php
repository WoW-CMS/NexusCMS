{{-- Section card --}}
<div class="bg-white rounded-lg shadow p-6">
    @if(!empty($section['title']))
        <h3 class="text-base font-semibold text-gray-800 mb-4">{{ $section['title'] }}</h3>
    @endif

    <div class="space-y-5">
        @foreach($section['fields'] ?? [] as $field)
            @php
                $type = $field['type'] ?? 'text';
                // Normalize 'help' → 'hint' so both keys work in templates
                if (!isset($field['hint']) && isset($field['help'])) {
                    $field['hint'] = $field['help'];
                }
                // Map 'checkbox' to 'toggle' (aliases)
                if ($type === 'checkbox') {
                    $type = 'toggle';
                    $field['type'] = 'toggle';
                }
                $templateExists = view()->exists("admin::generic-crud.fields.{$type}");
            @endphp
            @if($templateExists)
                @include("admin::generic-crud.fields.{$type}", compact('field', 'record'))
            @else
                {{-- Fallback for unknown field types --}}
                @include("admin::generic-crud.fields.text", ['field' => array_merge($field, ['type' => 'text']), 'record' => $record])
            @endif
        @endforeach
    </div>
</div>
