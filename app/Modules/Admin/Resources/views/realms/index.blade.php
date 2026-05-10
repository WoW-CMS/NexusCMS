@extends('admin::layouts.app')

@section('title', 'Realm Management')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Realm Management</h1>
                <p class="text-sm text-gray-500 mt-0.5">Listado y administracion de realms registrados</p>
            </div>
            <a href="{{ route('admin.realms.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                <i class="fas fa-plus"></i>
                Nuevo Realm
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">
        @if(session('success'))
            <div class="p-4 bg-green-50 border-l-4 border-green-500 rounded-lg text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-lg text-red-800 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if(session('soap_test_log'))
            @php
                $soapLog = session('soap_test_log');
            @endphp
            <div class="bg-slate-900 border border-slate-700 rounded-xl overflow-hidden">
                <div class="px-6 py-3 border-b border-slate-700 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-100">SOAP Server Info Log</h3>
                    <span class="text-xs text-slate-400">{{ $soapLog['timestamp'] ?? '' }}</span>
                </div>
                <div class="px-6 py-4 space-y-2 text-xs">
                    <div class="text-slate-300"><span class="font-semibold text-slate-100">Realm:</span> {{ $soapLog['realm'] ?? '-' }}</div>
                    <div class="text-slate-300"><span class="font-semibold text-slate-100">Command:</span> {{ $soapLog['command'] ?? '-' }}</div>
                    <pre class="mt-2 p-3 rounded bg-black/60 border border-slate-800 text-emerald-300 whitespace-pre-wrap">{{ $soapLog['response'] ?? '-' }}</pre>
                </div>
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-900">Realms registrados</h2>
                <span class="text-xs font-medium text-gray-500">{{ $realms->total() }} total</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Realm</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Host:Port</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Emulator</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Databases</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($realms as $realm)
                            @php
                                $auth = json_decode($realm->auth_database ?? '{}', true) ?: [];
                                $characters = json_decode($realm->character_database ?? '{}', true) ?: [];
                                $world = json_decode($realm->world_database ?? '{}', true) ?: [];
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $realm->name }}</div>
                                    <div class="text-xs text-gray-500">Expansion {{ $realm->expansion }}{{ $realm->bnet ? ' - BNET' : '' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $realm->hostname }}:{{ $realm->port }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $realm->emulator }}</td>
                                <td class="px-6 py-4 text-xs text-gray-600 space-y-1">
                                    <div><span class="font-semibold text-gray-700">Auth:</span> {{ $auth['database'] ?? '-' }} @ {{ $auth['host'] ?? '-' }}</div>
                                    <div><span class="font-semibold text-gray-700">Characters:</span> {{ $characters['database'] ?? '-' }} @ {{ $characters['host'] ?? '-' }}</div>
                                    <div><span class="font-semibold text-gray-700">World:</span> {{ $world['database'] ?? '-' }} @ {{ $world['host'] ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('admin.realms.soap-test', $realm->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 text-xs font-medium">
                                                <i class="fas fa-terminal"></i>
                                                SOAP Test
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.realms.edit', $realm->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 text-xs font-medium">
                                            <i class="fas fa-pen"></i>
                                            Editar
                                        </a>
                                        <form action="{{ route('admin.realms.destroy', $realm->id) }}" method="POST" onsubmit="return confirm('Esta accion eliminara el realm. Continuar?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 text-xs font-medium">
                                                <i class="fas fa-trash"></i>
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">No hay realms registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $realms->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
