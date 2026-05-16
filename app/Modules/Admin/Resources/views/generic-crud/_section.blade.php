{{-- Section card --}}
<div class="bg-white rounded-lg shadow p-6">
    @if(!empty($section['title']))
        <h3 class="text-base font-semibold text-gray-800 mb-4">{{ $section['title'] }}</h3>
    @endif

    <div class="space-y-5">
        @foreach($section['fields'] ?? [] as $field)
            @php $type = $field['type'] ?? 'text'; @endphp
            @include("admin::generic-crud.fields.{$type}", compact('field', 'record'))
        @endforeach
    </div>
</div>
