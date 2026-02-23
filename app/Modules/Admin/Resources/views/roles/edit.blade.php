@extends('admin::layouts.app')

@section('title', 'Editar Rol')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Edit Role: {{ $role->name }}</h1>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm font-medium">
            Back to Roles
        </a>
        <a href="{{ route('admin.permissions') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm font-medium">
            View Permissions
        </a>
    </div>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800">Role Details</h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Role Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800">Permissions (Drag & Drop)</h2>
                <p class="text-sm text-gray-600">Drag permissions between lists to update the role.</p>
            </div>
            <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Available Permissions</h3>
                    <div id="available" class="h-[300px] overflow-y-auto p-3 border border-gray-200 rounded-lg bg-gray-50 flex flex-col gap-2">
                        @php
                            $selectedSet = collect($selected);
                        @endphp
                        @foreach($permissions as $permission)
                            @if(!$selectedSet->contains($permission->name))
                                <div class="perm-item flex items-center justify-between p-2 bg-white border border-gray-200 rounded cursor-move"
                                     draggable="true"
                                     data-name="{{ $permission->name }}">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 bg-blue-100 text-blue-600 rounded flex items-center justify-center">
                                            <i class="fas fa-key"></i>
                                        </div>
                                        <span class="text-sm text-gray-800">{{ $permission->name }}</span>
                                    </div>
                                    <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">{{ explode('.', $permission->name)[0] }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Role Permissions</h3>
                    <div id="selected" class="h-[300px] overflow-y-auto p-3 border border-blue-300 rounded-lg bg-blue-50 flex flex-col gap-2">
                        @forelse($permissions as $permission)
                            @if($selectedSet->contains($permission->name))
                                <div class="perm-item flex items-center justify-between p-2 bg-white border border-gray-200 rounded cursor-move"
                                     draggable="true"
                                     data-name="{{ $permission->name }}">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 bg-blue-100 text-blue-600 rounded flex items-center justify-center">
                                            <i class="fas fa-key"></i>
                                        </div>
                                        <span class="text-sm text-gray-800">{{ $permission->name }}</span>
                                    </div>
                                    <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">{{ explode('.', $permission->name)[0] }}</span>
                                </div>
                            @endif
                        @empty
                            <div class="text-sm text-blue-700" id="selected-empty">Drag permissions here to add them to the role</div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex items-center justify-end gap-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                    Save Changes
                </button>
            </div>
        </div>

        <div id="hidden-inputs">
            @foreach($selected as $name)
                <input type="hidden" name="permissions[]" value="{{ $name }}">
            @endforeach
        </div>
    </form>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const available = document.getElementById('available');
    const selected = document.getElementById('selected');
    const hiddenInputs = document.getElementById('hidden-inputs');
    
    function createEmptyPlaceholder() {
        let placeholder = selected.querySelector('#selected-empty');
        if (!placeholder) {
            placeholder = document.createElement('div');
            placeholder.id = 'selected-empty';
            placeholder.className = 'text-sm text-blue-700';
            placeholder.textContent = 'Drag permissions here to add them to the role';
            selected.appendChild(placeholder);
        }
    }

    function updateHiddenInputs() {
        hiddenInputs.innerHTML = '';
        const items = Array.from(selected.querySelectorAll('.perm-item'));
        items.forEach(item => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'permissions[]';
            input.value = item.dataset.name;
            hiddenInputs.appendChild(input);
        });

        // Mostrar placeholder si no hay permisos
        if (items.length === 0) {
            createEmptyPlaceholder();
        } else {
            const placeholder = selected.querySelector('#selected-empty');
            if (placeholder) placeholder.remove();
        }
    }

    function enableDragAndDrop(container) {
        container.addEventListener('dragstart', (e) => {
            const target = e.target.closest('.perm-item');
            if (!target) return;
            e.dataTransfer.setData('text/plain', target.dataset.name);
            e.dataTransfer.dropEffect = 'move';
            container._dragging = target;
        });

        container.addEventListener('dragover', (e) => {
            e.preventDefault();
            container.classList.add('ring-2', 'ring-blue-300');
        });

        container.addEventListener('dragleave', () => {
            container.classList.remove('ring-2', 'ring-blue-300');
        });

        container.addEventListener('drop', (e) => {
            e.preventDefault();
            container.classList.remove('ring-2', 'ring-blue-300');
            const dragging = available._dragging || selected._dragging;
            if (dragging && dragging.parentElement !== container) {
                container.appendChild(dragging);
            }
            available._dragging = null;
            selected._dragging = null;
            updateHiddenInputs();
            enableDoubleClickAll();
        });
    }

    function enableDoubleClick(item) {
        item.addEventListener('dblclick', () => {
            const targetContainer = item.parentElement.id === 'available' ? selected : available;
            targetContainer.appendChild(item);
            updateHiddenInputs();
            enableDoubleClickAll();
        });
    }

    function enableDoubleClickAll() {
        document.querySelectorAll('.perm-item').forEach(item => {
            // Remueve listener anterior para evitar duplicados
            item.replaceWith(item.cloneNode(true));
        });
        document.querySelectorAll('.perm-item').forEach(enableDoubleClick);
    }

    enableDragAndDrop(available);
    enableDragAndDrop(selected);

    enableDoubleClickAll();
    updateHiddenInputs();
});
</script>
@endsection

