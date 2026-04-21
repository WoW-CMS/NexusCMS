@extends('admin::layouts.app')

@section('title', 'Nuevo Realm')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Nuevo Realm</h1>
                <p class="text-sm text-gray-500 mt-0.5">Alta de conexiones Auth, Characters y World</p>
            </div>
            <a href="{{ route('admin.realms.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">
                <i class="fas fa-arrow-left"></i>
                Volver al listado
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">
        @if($errors->any())
            <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-lg text-red-800 text-sm">
                <p class="font-semibold mb-2">No se pudo crear el realm:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-base font-semibold text-gray-900">Formulario de alta</h2>
            </div>

            <form action="{{ route('admin.realms.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hostname Realm</label>
                        <input type="text" name="hostname" value="{{ old('hostname') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Puerto Realm</label>
                        <input type="number" name="port" value="{{ old('port', 8085) }}" min="1" max="65535" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expansion</label>
                        <select name="expansion" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                            @foreach(\App\Enums\WoWConstants::EXPANSION_NAMES as $expansionId => $expansionName)
                                <option value="{{ $expansionId }}" {{ (int) old('expansion', \App\Enums\WoWConstants::EXPANSION_CATA) === (int) $expansionId ? 'selected' : '' }}>
                                    {{ $expansionName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Emulador</label>
                        <select id="emulator_select" name="emulator" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                            @foreach(\App\Enums\Emulator::LABELS as $emulatorValue => $emulatorLabel)
                                <option value="{{ $emulatorValue }}" {{ old('emulator', \App\Enums\Emulator::TRINITYCORE->value) === $emulatorValue ? 'selected' : '' }}>
                                    {{ $emulatorLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2 pt-7">
                        <input type="checkbox" id="bnet" name="bnet" value="1" {{ old('bnet') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="bnet" class="text-sm font-medium text-gray-700">Battle.net habilitado</label>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Consola del Realm</h3>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                            <input type="text" name="console_hostname" value="{{ old('console_hostname') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Puerto</label>
                            <input type="number" name="console_port" value="{{ old('console_port', 3443) }}" min="1" max="65535" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                            <input type="text" name="console_username" value="{{ old('console_username') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="console_password" value="{{ old('console_password') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">URN</label>
                            <select id="console_urn_select" name="console_urn" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                                @foreach(\App\Enums\Emulator::URN as $emulatorValue => $urnValue)
                                    <option value="{{ $urnValue }}" {{ old('console_urn', \App\Enums\Emulator::TRINITYCORE->value) === $urnValue ? 'selected' : '' }}>
                                        {{ \App\Enums\Emulator::LABELS[$emulatorValue] ?? $emulatorValue }} ({{ $urnValue }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                @php
                    $sections = [
                        'auth' => 'Auth Database',
                        'characters' => 'Characters Database',
                        'world' => 'World Database',
                    ];
                @endphp

                @foreach($sections as $key => $label)
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-semibold text-gray-800 mb-4">{{ $label }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                                <input type="text" name="{{ $key }}[host]" value="{{ old($key . '.host') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Puerto</label>
                                <input type="number" name="{{ $key }}[port]" value="{{ old($key . '.port', 3306) }}" min="1" max="65535" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Database</label>
                                <input type="text" name="{{ $key }}[database]" value="{{ old($key . '.database') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                                <input type="text" name="{{ $key }}[username]" value="{{ old($key . '.username') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <input type="password" name="{{ $key }}[password]" value="{{ old($key . '.password') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Charset</label>
                                <input type="text" name="{{ $key }}[charset]" value="{{ old($key . '.charset', 'utf8mb4') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Collation</label>
                                <input type="text" name="{{ $key }}[collation]" value="{{ old($key . '.collation', 'utf8mb4_unicode_ci') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                        <i class="fas fa-plus"></i>
                        Crear Realm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const emulatorSelect = document.getElementById('emulator_select');
        const urnSelect = document.getElementById('console_urn_select');

        if (!emulatorSelect || !urnSelect) {
            return;
        }

        emulatorSelect.addEventListener('change', function () {
            const emulatorValue = emulatorSelect.value;
            const hasMatchingUrn = Array.from(urnSelect.options).some(option => option.value === emulatorValue);

            if (hasMatchingUrn) {
                urnSelect.value = emulatorValue;
            }
        });
    });
</script>
@endpush
