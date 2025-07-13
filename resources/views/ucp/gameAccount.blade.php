@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-900 to-gray-950 pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            @include('ucp.components.sidebar')

            <div class="flex-1 space-y-6">
                <!-- Header Section with Stats Summary -->
                <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 border border-gray-700 rounded-3xl p-8 shadow-2xl backdrop-blur-lg">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 mb-2">
                                Linked Accounts
                            </h2>
                            <p class="text-gray-400 text-sm">Manage all your game accounts from one place</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            @if(!session('error'))
                            <a 
                                href="{{ route('ucp.gameaccount.create') }}"                               
                                class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-700 text-white rounded-lg hover:from-indigo-500 hover:to-purple-600 transition-all duration-300 font-medium shadow-lg"
                                <i class="fas fa-plus mr-2"></i> Register new account
                            </a>
                            @else
                            <button 
                                disabled                           
                                class="px-5 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 text-gray-400 rounded-lg cursor-not-allowed transition-all duration-300 font-medium shadow-lg opacity-75"
                            >
                                <i class="fas fa-plus mr-2"></i> Register new account
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Stats Cards Row -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-800/50 rounded-lg p-4 border border-gray-700 flex items-center">
                            <div class="rounded-full bg-blue-500/20 p-3 mr-4">
                                <i class="fas fa-gamepad text-blue-400"></i>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs">Total Accounts</p>
                                <p class="text-xl font-bold text-white">{{ count($gameAccounts ?? []) }}</p>
                            </div>
                        </div>
                        <div class="bg-gray-800/50 rounded-lg p-4 border border-gray-700 flex items-center">
                            <div class="rounded-full bg-purple-500/20 p-3 mr-4">
                                <i class="fas fa-users text-purple-400"></i>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs">Total Characters</p>
                                <p class="text-xl font-bold text-white">{{ $gameAccounts ? array_sum(array_map(function($acc) { return count($acc['characters'] ?? []); }, $gameAccounts)) : 0 }}</p>
                            </div>
                        </div>
                        <div class="bg-gray-800/50 rounded-lg p-4 border border-gray-700 flex items-center">
                            <div class="rounded-full bg-amber-500/20 p-3 mr-4">
                                <i class="fas fa-clock text-amber-400"></i>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs">Last Connection</p>
                                <p class="text-sm font-medium text-white">{{ $gameAccounts ? (collect($gameAccounts)->max('last_login') ?? 'Nunca') : 'Nunca' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Game Accounts Section -->
                <div class="bg-gray-800/30 backdrop-blur-sm rounded-xl border border-gray-800 p-6 shadow-xl">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-id-card text-indigo-400 mr-3"></i> Your Game Accounts
                        </h3>
                    </div>

                    @if(session('error'))
                    <div class="bg-red-900/50 border border-red-700 rounded-lg p-4 mb-6 text-white">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-400 mr-3 text-xl"></i>
                            <div>
                                <h4 class="font-semibold">Error de conexión</h4>
                                <p class="text-red-200">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(session('warning'))
                    <div class="bg-yellow-900/50 border border-yellow-700 rounded-lg p-4 mb-6 text-white">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-yellow-400 mr-3 text-xl"></i>
                            <div>
                                <h4 class="font-semibold">Advertencia</h4>
                                <p class="text-yellow-200">{{ session('warning') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Game Accounts Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @forelse ($gameAccounts ?? [] as $account)
                            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl p-6 border border-gray-700 hover:border-indigo-500/50 transition-all duration-300 shadow-lg group relative overflow-hidden">
                                <!-- Background decoration -->
                                <div class="absolute -right-12 -top-12 w-24 h-24 bg-indigo-600/10 rounded-full blur-xl group-hover:bg-indigo-600/20 transition-all duration-500"></div>
                                
                                <div class="flex items-center justify-between mb-5 relative">
                                    <div class="flex items-center space-x-4">
                                        <div class="bg-gradient-to-br from-indigo-600 to-purple-600 p-3 rounded-lg shadow-lg">
                                            <i class="fas fa-gamepad text-xl text-white"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-white group-hover:text-indigo-300 transition-colors">{{ $account['username'] }}</h3>
                                            <p class="text-sm text-gray-400">Created: {{ \Carbon\Carbon::parse($account['created_at'])->format('d M, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <button class="p-2 bg-gray-800/70 rounded-lg text-indigo-400 hover:text-white hover:bg-indigo-600 transition-all" title="Editar cuenta">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="p-2 bg-gray-800/70 rounded-lg text-red-400 hover:text-white hover:bg-red-600 transition-all" title="Eliminar cuenta">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-5">
                                    <div class="bg-gray-800/50 rounded-lg p-3 border border-gray-700">
                                        <p class="text-xs text-gray-500 mb-1">Status</p>
                                        <div class="flex items-center">
                                            <span class="w-2 h-2 rounded-full {{ $account['status'] === 0 ? 'bg-green-500' : 'bg-gray-500' }} mr-2"></span>
                                            <span class="text-sm font-medium {{ $account['status'] === 0 ? 'text-green-400' : 'text-gray-400' }}">
                                                {{ $account['status'] === 0 ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-800/50 rounded-lg p-3 border border-gray-700">
                                        <p class="text-xs text-gray-500 mb-1">Characters</p>
                                        <div class="flex items-center">
                                            <i class="fas fa-users text-indigo-400 mr-2 text-sm"></i>
                                            <span class="text-sm font-medium text-white">{{ count($account['characters'] ?? []) }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if(!empty($account['characters']))
                                <div class="mt-4 mb-5">
                                    <p class="text-xs text-gray-500 mb-2">Personajes en {{ $account['realm_name'] ?? 'Realm' }}</p>
                                    <div class="bg-gray-800/50 rounded-lg border border-gray-700 overflow-hidden">
                                        <div class="max-h-32 overflow-y-auto p-1">
                                            @foreach($account['characters'] as $character)
                                            <div class="flex items-center justify-between py-1 px-2 hover:bg-gray-700/50 rounded">
                                                <div class="flex items-center">
                                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mr-2 text-xs font-bold text-white">{{ $character->level }}</div>
                                                    <span class="text-sm font-medium text-white">{{ $character->name }}</span>
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ App\Helpers\RealmHelper::getWoWConstant('class', $character->class) }}
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Expansion</p>
                                        <p class="text-sm text-gray-300">
                                            <i class="fas fa-globe text-indigo-400 mr-2"></i>
                                            {{ App\Helpers\RealmHelper::getWoWConstant('expansion', $account['expansion']) }}
                                        </p>
                                    </div>
                                    <button class="px-4 py-2 bg-gradient-to-r from-indigo-600/20 to-purple-600/20 text-indigo-400 rounded-lg border border-indigo-500/20 hover:from-indigo-600/30 hover:to-purple-600/30 transition-all duration-300 text-sm">
                                        <i class="fas fa-sign-in-alt mr-1"></i> Manage
                                    </button>
                                </div>
                            </div>
                        @empty
                            @if(!session('error'))
                            <div class="col-span-2 bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl p-12 text-center border border-gray-800 relative overflow-hidden">
                                <!-- Background decoration -->
                                <div class="absolute -right-20 -top-20 w-40 h-40 bg-indigo-600/5 rounded-full blur-xl"></div>
                                <div class="absolute -left-20 -bottom-20 w-40 h-40 bg-purple-600/5 rounded-full blur-xl"></div>
                                
                                <div class="relative">
                                    <div class="inline-flex items-center justify-center p-6 bg-indigo-900/20 rounded-full mb-8 border border-indigo-800/30">
                                        <i class="fas fa-gamepad text-5xl text-indigo-400 opacity-80"></i>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-300 mb-4">No tienes cuentas vinculadas</h3>
                                    <p class="text-gray-500 max-w-md mx-auto mb-8">Vincula tu primera cuenta de juego para comenzar a jugar y gestionar todos tus personajes desde un solo lugar.</p>
                                    <a 
                                        href="{{ route('ucp.gameaccount.create') }}"
                                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-500 hover:to-purple-500 transition-all duration-300 font-medium shadow-lg shadow-indigo-600/20">
                                        <i class="fas fa-plus mr-2"></i> Crear mi primera cuenta
                                    </a>
                                </div>
                            </div>
                            @endif
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
