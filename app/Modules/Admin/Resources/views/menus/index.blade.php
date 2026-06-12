@extends('admin::layouts.app')

@section('title', 'Menu Manager')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Menu Manager</h1>
        <p class="text-sm text-gray-500">Drag items to reorder — click to edit — nest by dragging onto another item</p>
    </div>
    <div class="flex items-center gap-3">
        <button type="button" onclick="addNewItem('web')" class="px-3 py-2 rounded-lg text-sm bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200">
            <i class="fas fa-plus mr-1"></i> Add Web Item
        </button>
        <button type="button" onclick="addNewItem('ucp')" class="px-3 py-2 rounded-lg text-sm bg-green-50 text-green-700 hover:bg-green-100 border border-green-200">
            <i class="fas fa-plus mr-1"></i> Add UCP Item
        </button>
        <button type="submit" form="menu-form" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
            <i class="fas fa-save mr-2"></i> Save Menus
        </button>
    </div>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex items-center gap-3">
            <i class="fas fa-check-circle text-green-600"></i>
            <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <form id="menu-form" method="POST" action="{{ route('admin.menus.update') }}" class="space-y-6" onsubmit="syncEnabledCheckboxes()">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- WEB MENU --}}
            <div class="bg-white rounded-xl shadow border border-gray-200">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Web Menu</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Top navigation — frontend</p>
                    </div>
                    <button type="button" onclick="addNewItem('web')" class="px-3 py-1.5 rounded-lg text-xs bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200">
                        <i class="fas fa-plus mr-1"></i> Add Item
                    </button>
                </div>
                <div class="p-4">
                    <div id="web-menu-list" class="space-y-2 min-h-[100px]"
                         ondragover="handleDragOver(event)"
                         ondragleave="handleDragLeave(event)"
                         ondrop="handleDrop(event, 'web')">
                        @forelse($webMenu as $index => $item)
                            @include('admin::menus.partials.item', ['prefix' => 'web_items', 'index' => $index, 'item' => $item, 'menuType' => 'web'])
                        @empty
                            <div class="text-center py-8 text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-lg">
                                <i class="fas fa-bars text-2xl mb-2"></i>
                                <p>No items yet. Click "Add Item" to start building your menu.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- UCP MENU --}}
            <div class="bg-white rounded-xl shadow border border-gray-200">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">UCP Menu</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Sidebar navigation — user control panel</p>
                    </div>
                    <button type="button" onclick="addNewItem('ucp')" class="px-3 py-1.5 rounded-lg text-xs bg-green-50 text-green-700 hover:bg-green-100 border border-green-200">
                        <i class="fas fa-plus mr-1"></i> Add Item
                    </button>
                </div>
                <div class="p-4">
                    <div id="ucp-menu-list" class="space-y-2 min-h-[100px]"
                         ondragover="handleDragOver(event)"
                         ondragleave="handleDragLeave(event)"
                         ondrop="handleDrop(event, 'ucp')">
                        @forelse($ucpMenu as $index => $item)
                            @include('admin::menus.partials.item', ['prefix' => 'ucp_items', 'index' => $index, 'item' => $item, 'menuType' => 'ucp'])
                        @empty
                            <div class="text-center py-8 text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-lg">
                                <i class="fas fa-bars text-2xl mb-2"></i>
                                <p>No items yet. Click "Add Item" to start building your menu.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Route Picker Modal --}}
        <div id="route-picker-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 max-h-[80vh] flex flex-col">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800">Select Route</h3>
                    <button type="button" onclick="closeRoutePicker()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto flex-1">
                    <input type="text" id="route-search" placeholder="Search routes..." class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-4 text-sm" oninput="filterRoutes()">
                    <div id="route-list" class="space-y-1"></div>
                </div>
            </div>
        </div>

        {{-- Item Editor Sidebar --}}
        <div id="item-editor" class="hidden fixed inset-y-0 right-0 w-80 bg-white shadow-2xl z-50 flex flex-col border-l border-gray-200">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                <h3 class="font-bold text-gray-800">Edit Item</h3>
                <button type="button" onclick="closeEditor()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-4 space-y-4" id="editor-fields">
               <input type="hidden" id="edit-index" value="">
                <input type="hidden" id="edit-menu-type" value="">

                <div class="panel-section">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Label</label>
                    <input type="text" id="edit-label" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Menu label">
                </div>

                <div class="panel-section">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Route</label>
                    <div class="flex gap-2">
                        <input type="text" id="edit-route" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="route.name">
                        <button type="button" onclick="openRoutePicker()" class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm hover:bg-gray-200">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="panel-section">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">URL Override</label>
                    <input type="text" id="edit-url" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="/custom-url (optional)">
                </div>

                <div class="panel-section">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Icon (FontAwesome class)</label>
                    <input type="text" id="edit-icon" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="fas fa-home">
                    <p class="text-xs text-gray-400 mt-1">Examples: <code class="bg-gray-100 px-1 rounded">fas fa-home</code>, <code class="bg-gray-100 px-1 rounded">fas fa-users</code></p>
                </div>

                <div class="panel-section">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Module</label>
                    <select id="edit-module" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">Core / No Module</option>
                        @foreach($modules as $moduleName => $isEnabled)
                            <option value="{{ $moduleName }}">{{ $moduleName }} {{ $isEnabled ? '(enabled)' : '(disabled)' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="panel-section">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Auth Rule</label>
                    <select id="edit-auth" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="any">Any — visible to everyone</option>
                        <option value="auth">Auth — logged in users only</option>
                        <option value="guest">Guest — logged out users only</option>
                    </select>
                </div>

                <div class="panel-section">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Permission (optional)</label>
                    <input type="text" id="edit-permission" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="permission.name">
                </div>

                <div class="panel-section flex items-center justify-between">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Enabled</label>
                    <input type="checkbox" id="edit-enabled" class="w-5 h-5 rounded border-gray-300 text-blue-600">
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="button" onclick="saveEditedItem()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                        <i class="fas fa-save mr-2"></i>Apply Changes
                    </button>
                    <button type="button" onclick="deleteCurrentItem()" class="w-full mt-2 px-4 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 text-sm font-medium border border-red-200">
                        <i class="fas fa-trash mr-2"></i>Delete Item
                    </button>
                </div>
            </div>
        </div>
    </form>
</main>
@endsection

@php
$allRoutesForPicker = [];
foreach (['public', 'auth', 'ucp'] as $cat) {
    $allRoutesForPicker[$cat] = $availableRoutes[$cat] ?? [];
}
@endphp

@push('scripts')
<script>
    // ─── Data ───────────────────────────────────────────────────────────────────
    const availableRoutes = @json($allRoutesForPicker);
    const moduleRoutes = @json($moduleRoutes);
    const modules = @json($modules);

    let editingItemId = null;
    let editingMenuType = null;
    let itemCounter = Date.now();

    // ─── Drag& Drop ─────────────────────────────────────────────────────────
    function handleDragStart(event) {
        event.dataTransfer.setData('text/plain', event.target.dataset.itemId);
        event.dataTransfer.setData('text/menu', event.target.dataset.menuType);
        event.target.classList.add('opacity-50');
    }

    function handleDragEnd(event) {
        event.target.classList.remove('opacity-50');
        document.querySelectorAll('.drag-over').forEach(el => el.classList.remove('drag-over'));
    }

    function handleDragOver(event) {
        event.preventDefault();
        event.currentTarget.classList.add('drag-over');
    }

    function handleDragLeave(event) {
        event.currentTarget.classList.remove('drag-over');
    }

    function handleDrop(event, targetMenu) {
        event.preventDefault();
        event.currentTarget.classList.remove('drag-over');

        const itemId = event.dataTransfer.getData('text/plain');
        const sourceMenu = event.dataTransfer.getData('text/menu');

        const sourceList = document.getElementById(`${sourceMenu}-menu-list`);
        const targetList = document.getElementById(`${targetMenu}-menu-list`);
        const draggedEl = sourceList.querySelector(`[data-item-id="${itemId}"]`);

        if (!draggedEl) return;

        // If dropping on another item, make it a child
        const targetEl = event.target.closest('[data-item-id]');
        if (targetEl && targetEl !== draggedEl && targetEl.dataset.menuType === targetMenu) {
            moveToChildren(targetEl, draggedEl, sourceList, targetList);
        } else {
            // Move to end of target list
            targetList.appendChild(draggedEl);
            draggedEl.dataset.menuType = targetMenu;
        }

        reindexItems(sourceMenu);
        reindexItems(targetMenu);
    }

    function moveToChildren(targetEl, draggedEl, sourceList, targetList) {
        const childrenContainer = targetEl.querySelector('.children-container');
        if (!childrenContainer) return;

        draggedEl.dataset.menuType = targetEl.dataset.menuType;
        draggedEl.classList.add('child-item');
        childrenContainer.appendChild(draggedEl);
    }

    function reindexItems(menuType) {
        const list = document.getElementById(`${menuType}-menu-list`);
        const prefix = `${menuType}_items`;
        let index = 0;

        list.querySelectorAll(':scope > [data-item-id]').forEach(card => {
            card.querySelectorAll('[data-field]').forEach(input => {
                const field = input.dataset.field;
                const name = `${prefix}[${index}][${field}]`;
                input.name = name;
            });

            // Children
            const childrenContainer = card.querySelector('.children-container');
            if (childrenContainer) {
                let childIndex = 0;
                childrenContainer.querySelectorAll('[data-item-id]').forEach(childCard => {
                    childCard.querySelectorAll('[data-field]').forEach(input => {
                        const field = input.dataset.field;
                        const name = `${prefix}[${index}][children][${childIndex}][${field}]`;
                        input.name = name;
                    });
                    childIndex++;
                });
            }

            index++;
        });
    }

    // ─── Add Item ───────────────────────────────────────────────────────────
    function addNewItem(menuType) {
        const list = document.getElementById(`${menuType}-menu-list`);
        const emptyState = list.querySelector('.empty-state');
        if (emptyState) emptyState.remove();

        const id = `new_${itemCounter++}`;
        const index = list.querySelectorAll(':scope > [data-item-id]').length;
        const prefix = `${menuType}_items`;

        const card = createItemCard(id, index, {
            id,
            label: '',
            route: '',
            url: '',
            icon: '',
            module: '',
            auth: 'any',
            permission: '',
            enabled: true,
            children: [],
        }, menuType, prefix);

        list.appendChild(card);
 }

    function createItemCard(id, index, item, menuType, prefix) {
        const card = document.createElement('div');
        card.className = `menu-item-card bg-white border border-gray-200 rounded-lg p-3 ${item._isChild ? 'child-item' : ''}`;
        card.dataset.itemId = id;
        card.dataset.menuType = menuType;
        card.draggable = true;

        card.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="drag-handle flex-shrink-0 pt-1 text-gray-300 hover:text-gray-500" draggable="true"
 ondragstart="handleDragStart(event)" ondragend="handleDragEnd(event)">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <input type="text" data-field="label" value="${escapeHtml(item.label || '')}"
 placeholder="Label" onchange="updateCardLabel(this)"
                               class="font-semibold text-sm text-gray-800 bg-transparent border-none outline-none focus:ring-0 p-0 w-full">
<span class="icon-preview text-gray-400"><i class="${escapeHtml(item.icon || 'fas fa-link')}"></i></span>
                        ${item.module ? `<span class="module-badge ${(modules[item.module] ?? false) ? 'enabled' : 'disabled'}">${escapeHtml(item.module)}</span>` : ''}
                       <input type="checkbox" data-field="enabled" ${(item.enabled !== false) ? 'checked' : ''}
 class="w-4 h-4 rounded border-gray-300 text-blue-600 ml-auto" title="Enabled">
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        ${item.route ? `<span class="route-chip">${escapeHtml(item.route)}</span>` : ''}
                        ${item.url ? `<span class="text-gray-400">${escapeHtml(item.url)}</span>` : ''}
                        ${item.auth !== 'any' ? `<span class="bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded text-xs">${item.auth}</span>` : ''}
                    </div>
                   <div class="children-container mt-2 space-y-1"></div>
                </div>
                <div class="flex flex-col gap-1 flex-shrink-0">
                    <button type="button" onclick="openEditor('${id}', '${menuType}')" class="px-2 py-1 text-xs text-blue-600 hover:bg-blue-50 rounded" title="Edit">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button type="button" onclick="deleteItem('${id}', '${menuType}')" class="px-2 py-1 text-xs text-red-500 hover:bg-red-50 rounded" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        // Add hidden inputs
        ['label', 'route', 'url', 'icon', 'module', 'auth', 'permission', 'enabled', 'id'].forEach(field => {
            const input = document.createElement('input');
            input.type = field === 'enabled' ? 'checkbox' : 'hidden';
            input.dataset.field = field;
            input.name = `${prefix}[${index}][${field}]`;
            if (field === 'enabled') {
                input.checked = item.enabled !== false;
                input.className = 'w-4 h-4 rounded border-gray-300 text-blue-600';
            } else {
                input.value = item[field] ?? '';
            }
            card.appendChild(input);
        });

        // Add children recursively
        if (item.children && item.children.length > 0) {
            const container = card.querySelector('.children-container');
            item.children.forEach((child, ci) => {
                const childCard = createItemCard(`child_${id}_${ci}`, ci, { ...child, _isChild: true }, menuType, `${prefix}[${index}][children]`);
                container.appendChild(childCard);
            });
        }

        return card;
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function updateCardLabel(input) {
        const card = input.closest('.menu-item-card');
        const labelSpan = card.querySelector('.item-label');
        if (labelSpan) labelSpan.textContent = input.value;
    }

    // ─── Item Editor ──────────────────────────────────────────────────────────
    function openEditor(itemId, menuType) {
        editingItemId = itemId;
        editingMenuType = menuType;

        const list = document.getElementById(`${menuType}-menu-list`);
        const card = list.querySelector(`[data-item-id="${itemId}"]`);

        document.getElementById('edit-label').value = card.querySelector('[data-field="label"]').value;
        document.getElementById('edit-route').value = card.querySelector('[data-field="route"]').value;
        document.getElementById('edit-url').value = card.querySelector('[data-field="url"]').value;
        document.getElementById('edit-icon').value = card.querySelector('[data-field="icon"]').value;
        document.getElementById('edit-module').value = card.querySelector('[data-field="module"]').value;
        document.getElementById('edit-auth').value = card.querySelector('[data-field="auth"]').value;
        document.getElementById('edit-permission').value = card.querySelector('[data-field="permission"]').value;
        document.getElementById('edit-enabled').checked = card.querySelector('[data-field="enabled"]').checked;

        document.getElementById('item-editor').classList.remove('hidden');
    }

    function closeEditor() {
        document.getElementById('item-editor').classList.add('hidden');
        editingItemId = null;
        editingMenuType = null;
    }

    function saveEditedItem() {
        if (!editingItemId) return;

        const list = document.getElementById(`${editingMenuType}-menu-list`);
        const card = list.querySelector(`[data-item-id="${editingItemId}"]`);

        card.querySelector('[data-field="label"]').value = document.getElementById('edit-label').value;
        card.querySelector('[data-field="route"]').value = document.getElementById('edit-route').value;
        card.querySelector('[data-field="url"]').value = document.getElementById('edit-url').value;
        card.querySelector('[data-field="icon"]').value = document.getElementById('edit-icon').value;
        card.querySelector('[data-field="module"]').value = document.getElementById('edit-module').value;
        card.querySelector('[data-field="auth"]').value = document.getElementById('edit-auth').value;
        card.querySelector('[data-field="permission"]').value = document.getElementById('edit-permission').value;
        card.querySelector('[data-field="enabled"]').checked = document.getElementById('edit-enabled').checked;

        closeEditor();
    }

    function deleteCurrentItem() {
        if (!editingItemId) return;
        deleteItem(editingItemId, editingMenuType);
        closeEditor();
    }

    function deleteItem(itemId, menuType) {
        const list = document.getElementById(`${menuType}-menu-list`);
        const card = list.querySelector(`[data-item-id="${itemId}"]`);
        if (card) card.remove();

        if (list.children.length === 0) {
            list.innerHTML = `
                <div class="empty-state text-center py-8 text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-lg">
                    <i class="fas fa-bars text-2xl mb-2"></i>
                    <p>No items yet. Click "Add Item" to start building your menu.</p>
                </div>
            `;
        }

        reindexItems(menuType);
    }

    // ─── Route Picker ─────────────────────────────────────────────────────────
    function openRoutePicker() {
        document.getElementById('route-picker-modal').classList.remove('hidden');
        document.getElementById('route-search').value = '';
        renderRouteList(availableRoutes);
    }

    function closeRoutePicker() {
        document.getElementById('route-picker-modal').classList.add('hidden');
    }

    function renderRouteList(routes) {
        const container = document.getElementById('route-list');
        container.innerHTML = '';

        ['public', 'auth', 'ucp'].forEach(cat => {
            if (!routes[cat] || routes[cat].length === 0) return;

            const section = document.createElement('div');
            section.className = 'mb-4';

            const catLabel = cat === 'public' ? 'Public Routes' : cat === 'auth' ? 'Auth Required' : 'UCP Routes';
            section.innerHTML = `<p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">${catLabel}</p>`;

            routes[cat].forEach(route => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'w-full text-left px-3 py-2 rounded-lg hover:bg-blue-50 text-sm flex items-center justify-between gap-2';
                btn.innerHTML = `
                    <span class="flex-1 min-w-0">
                        <span class="font-medium text-gray-800 truncate block">${route.name}</span>
                        <span class="text-gray-400 text-xs truncate block">${route.uri}</span>
                    </span>
                    <span class="text-xs text-gray-400 flex-shrink-0">${route.methods[0]}</span>
                `;
                btn.onclick = () => selectRoute(route.name);
                section.appendChild(btn);
            });

            container.appendChild(section);
        });
    }

    function filterRoutes() {
        const query = document.getElementById('route-search').value.toLowerCase();
        const filtered = {};
        ['public', 'auth', 'ucp'].forEach(cat => {
            if (!availableRoutes[cat]) return;
            filtered[cat] = availableRoutes[cat].filter(r =>
                r.name.toLowerCase().includes(query) || r.uri.toLowerCase().includes(query)
            );
        });
        renderRouteList(filtered);
    }

    function selectRoute(routeName) {
        document.getElementById('edit-route').value = routeName;
        closeRoutePicker();
    }

    // ─── Sync enabled checkboxes before submit ─────────────────────────────
    function syncEnabledCheckboxes() {
        document.querySelectorAll('.menu-item-card').forEach(card => {
            const visible = card.querySelector('input[data-field="enabled"][type="checkbox"]');
            const hidden = card.querySelector('input[data-field="enabled"][type="hidden"]');
            if (visible && hidden) {
                hidden.value = visible.checked ? '1' : '0';
            }
        });
    }

    // ─── Init: bind drag events to existing cards ────────────────────────────
    document.querySelectorAll('[data-item-id]').forEach(card => {
        card.addEventListener('dragstart', handleDragStart);
        card.addEventListener('dragend', handleDragEnd);
    });
</script>
@endpush
