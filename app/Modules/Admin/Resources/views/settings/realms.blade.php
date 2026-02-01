@extends('admin::settings.index')

@section('settings_content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">Realm Configuration</h2>
        <p class="text-sm text-gray-600 mt-1">Configure your game server realms</p>
    </div>
    <div class="p-6">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Active Realms</h3>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>Add Realm
            </button>
        </div>
        <div class="space-y-4">
            <!-- Realm 1 -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <h4 class="font-bold text-gray-800">Realm 1 - FireStorm</h4>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-xs font-medium">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-xs font-medium">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
                <div class="grid md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Host:</span>
                        <span class="font-semibold text-gray-800 ml-2">127.0.0.1</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Port:</span>
                        <span class="font-semibold text-gray-800 ml-2">8085</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Version:</span>
                        <span class="font-semibold text-gray-800 ml-2">3.3.5a</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Type:</span>
                        <span class="font-semibold text-gray-800 ml-2">PvP</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Rates:</span>
                        <span class="font-semibold text-gray-800 ml-2">1x</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Players:</span>
                        <span class="font-semibold text-gray-800 ml-2">850/2000</span>
                    </div>
                </div>
            </div>

            <!-- Realm 2 -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <h4 class="font-bold text-gray-800">Realm 2 - Frostmourne</h4>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-xs font-medium">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-xs font-medium">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
                <div class="grid md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Host:</span>
                        <span class="font-semibold text-gray-800 ml-2">127.0.0.1</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Port:</span>
                        <span class="font-semibold text-gray-800 ml-2">8086</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Version:</span>
                        <span class="font-semibold text-gray-800 ml-2">3.3.5a</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Type:</span>
                        <span class="font-semibold text-gray-800 ml-2">PvE</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Rates:</span>
                        <span class="font-semibold text-gray-800 ml-2">5x</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Players:</span>
                        <span class="font-semibold text-gray-800 ml-2">420/1000</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
