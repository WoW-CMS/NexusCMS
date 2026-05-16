{{--
    Generic form partial. Iterates over $config['form']['sections'] and renders each field.
    Supports layouts: "single" and "sidebar".
--}}
@php
    $layout = $config['form']['layout'] ?? 'single';
    $sections = $config['form']['sections'] ?? [];

    if ($layout === 'sidebar') {
        $mainSections    = array_filter($sections, fn($s) => ($s['position'] ?? 'main') === 'main');
        $sidebarSections = array_filter($sections, fn($s) => ($s['position'] ?? 'main') === 'sidebar');
    }
@endphp

@if($layout === 'sidebar')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main column --}}
    <div class="lg:col-span-2 space-y-6">
        @foreach($mainSections as $section)
            @include('admin::generic-crud._section', compact('section', 'record'))
        @endforeach
    </div>
    {{-- Sidebar column --}}
    <div class="space-y-6">
        @foreach($sidebarSections as $section)
            @include('admin::generic-crud._section', compact('section', 'record'))
        @endforeach
    </div>
</div>
@else
<div class="space-y-6">
    @foreach($sections as $section)
        @include('admin::generic-crud._section', compact('section', 'record'))
    @endforeach
</div>
@endif
