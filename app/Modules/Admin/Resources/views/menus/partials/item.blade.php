@php
$itemId = $item['id'] ?? "item_{$index}";
$isChild = $item['_isChild'] ?? false;
$enabled = !empty($item['enabled']);
@endphp

<div class="menu-item-card bg-white border border-gray-200 rounded-lg p-3 {{ $isChild ? 'child-item' : '' }}"
     data-item-id="{{ $itemId }}"
     data-menu-type="{{ $menuType }}"
     draggable="true"
     ondragstart="handleDragStart(event)"
     ondragend="handleDragEnd(event)">

    <div class="flex items-start gap-3">
        <div class="drag-handle flex-shrink-0 pt-1 text-gray-300 hover:text-gray-500">
            <i class="fas fa-bars"></i>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1 flex-wrap">
                <input type="text"
                       data-field="label"
                       value="{{ $item['label'] ?? '' }}"
                       placeholder="Label"
                       class="font-semibold text-sm text-gray-800 bg-transparent border-none outline-none focus:ring-0 p-0 w-full min-w-[100px]">

                <span class="icon-preview text-gray-400">
                    <i class="{{ $item['icon'] ?? 'fas fa-link' }}"></i>
                </span>

                @if(!empty($item['module']) && isset($modules[$item['module']]))
                    <span class="module-badge {{ $modules[$item['module']] ? 'enabled' : 'disabled' }}">
                        {{ $item['module'] }}
                    </span>
                @endif

                {{-- Visible checkbox (UI only, no name) --}}
                <input type="checkbox"
                       data-field="enabled"
                       class="w-4 h-4 rounded border-gray-300 text-blue-600 ml-auto"
                       title="Enabled"
                       {{ $enabled ? 'checked' : '' }}>
            </div>

            <div class="flex items-center gap-2 text-xs text-gray-500 flex-wrap">
                @if(!empty($item['route']))
                    <span class="route-chip">{{ $item['route'] }}</span>
                @endif
                @if(!empty($item['url']))
                    <span class="text-gray-400">{{ $item['url'] }}</span>
                @endif
                @if(($item['auth'] ?? 'any') !== 'any')
                    <span class="bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded text-xs">{{ $item['auth'] }}</span>
                @endif
                @if(!empty($item['permission']))
                    <span class="text-gray-400">{{ $item['permission'] }}</span>
                @endif
            </div>

            @if(!empty($item['children']))
                <div class="children-container mt-2 space-y-1 pl-3 border-l-2 border-gray-200">
                    @foreach($item['children'] as $childIndex => $childItem)
                        @include('admin::menus.partials.item', [
                            'prefix' => "{$prefix}[{$index}][children]",
                            'index' => $childIndex,
                            'item' => array_merge($childItem, ['_isChild' => true]),
                            'menuType' => $menuType,
                            'modules' => $modules
                        ])
                    @endforeach
                </div>
            @else
                <div class="children-container mt-2 space-y-1"></div>
            @endif
        </div>

        <div class="flex flex-col gap-1 flex-shrink-0">
            <button type="button"
                    onclick="openEditor('{{ $itemId }}', '{{ $menuType }}')"
                    class="px-2 py-1 text-xs text-blue-600 hover:bg-blue-50 rounded"
                    title="Edit">
                <i class="fas fa-pen"></i>
            </button>
            <button type="button"
                    onclick="deleteItem('{{ $itemId }}', '{{ $menuType }}')"
                    class="px-2 py-1 text-xs text-red-500 hover:bg-red-50 rounded"
                    title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>

    {{-- Hidden inputs for form submission --}}
    <input type="hidden" data-field="id" name="{{ $prefix }}[{{ $index }}][id]" value="{{ $itemId }}">
    <input type="hidden" data-field="label" name="{{ $prefix }}[{{ $index }}][label]" value="{{ $item['label'] ?? '' }}">
    <input type="hidden" data-field="route" name="{{ $prefix }}[{{ $index }}][route]" value="{{ $item['route'] ?? '' }}">
    <input type="hidden" data-field="url" name="{{ $prefix }}[{{ $index }}][url]" value="{{ $item['url'] ?? '' }}">
    <input type="hidden" data-field="icon" name="{{ $prefix }}[{{ $index }}][icon]" value="{{ $item['icon'] ?? '' }}">
    <input type="hidden" data-field="module" name="{{ $prefix }}[{{ $index }}][module]" value="{{ $item['module'] ?? '' }}">
    <input type="hidden" data-field="auth" name="{{ $prefix }}[{{ $index }}][auth]" value="{{ $item['auth'] ?? 'any' }}">
    <input type="hidden" data-field="permission" name="{{ $prefix }}[{{ $index }}][permission]" value="{{ $item['permission'] ?? '' }}">
    <input type="hidden" data-field="enabled" name="{{ $prefix }}[{{ $index }}][enabled]" value="{{ $enabled ? '1' : '0' }}">
</div>
