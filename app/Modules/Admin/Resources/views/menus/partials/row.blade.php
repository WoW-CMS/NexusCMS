<tr>
    <td class="px-3 py-2">
        <input type="text" name="{{ $prefix }}[{{ $index }}][label]" value="{{ $item['label'] ?? '' }}" class="w-full rounded border-gray-300" placeholder="Label">
    </td>
    <td class="px-3 py-2">
        @php
            $selectedModule = $item['module'] ?? '';
            $moduleExists = $selectedModule === '' || array_key_exists($selectedModule, $modules);
            $moduleEnabled = $selectedModule === '' || (($modules[$selectedModule] ?? false) === true);
        @endphp
        <div class="space-y-1">
            <select name="{{ $prefix }}[{{ $index }}][module]" class="w-full rounded border-gray-300">
                <option value="">Core / No Module</option>
                @foreach($modules as $moduleName => $isEnabled)
                    <option value="{{ $moduleName }}" {{ $selectedModule === $moduleName ? 'selected' : '' }}>
                        {{ $moduleName }} {{ $isEnabled ? '(enabled)' : '(disabled)' }}
                    </option>
                @endforeach
            </select>
            @if(!$moduleExists)
                <p class="text-xs text-red-600">Assigned module does not exist.</p>
            @elseif(!$moduleEnabled)
                <p class="text-xs text-amber-600">Assigned module is disabled.</p>
            @endif
        </div>
    </td>
    <td class="px-3 py-2">
        <input type="text" name="{{ $prefix }}[{{ $index }}][route]" value="{{ $item['route'] ?? '' }}" class="w-full rounded border-gray-300" placeholder="route.name">
    </td>
    <td class="px-3 py-2">
        <input type="text" name="{{ $prefix }}[{{ $index }}][url]" value="{{ $item['url'] ?? '' }}" class="w-full rounded border-gray-300" placeholder="/custom-url">
    </td>
    <td class="px-3 py-2">
        <input type="text" name="{{ $prefix }}[{{ $index }}][icon]" value="{{ $item['icon'] ?? '' }}" class="w-full rounded border-gray-300" placeholder="fas fa-home">
    </td>
    <td class="px-3 py-2">
        <select name="{{ $prefix }}[{{ $index }}][auth]" class="w-full rounded border-gray-300">
            <option value="any" {{ ($item['auth'] ?? 'any') === 'any' ? 'selected' : '' }}>Any</option>
            <option value="auth" {{ ($item['auth'] ?? 'any') === 'auth' ? 'selected' : '' }}>Auth</option>
            <option value="guest" {{ ($item['auth'] ?? 'any') === 'guest' ? 'selected' : '' }}>Guest</option>
        </select>
    </td>
    <td class="px-3 py-2">
        <input type="text" name="{{ $prefix }}[{{ $index }}][permission]" value="{{ $item['permission'] ?? '' }}" class="w-full rounded border-gray-300" placeholder="permission.name">
    </td>
    <td class="px-3 py-2 text-center">
        <input type="checkbox" name="{{ $prefix }}[{{ $index }}][enabled]" value="1" {{ ($item['enabled'] ?? true) ? 'checked' : '' }}>
    </td>
    <td class="px-3 py-2 text-right">
        <button type="button" class="px-2 py-1 text-xs rounded bg-red-50 text-red-700 hover:bg-red-100" onclick="this.closest('tr').remove()">Remove</button>
    </td>
</tr>
