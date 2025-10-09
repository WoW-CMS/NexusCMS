@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-900 to-gray-950 pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            @include('ucp.components.sidebar')

            <div class="flex-1 space-y-6">
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 border border-gray-700 rounded-3xl p-8 shadow-2xl backdrop-blur-lg">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 mb-2">
                                Gestionar Cuenta: {{ $account->username ?? 'Cuenta' }}
                            </h2>
                            <p class="text-gray-400 text-sm">Administra tu cuenta de juego y configuraciones</p>
                        </div>
                        <div class="mt-4 md:mt-0 flex space-x-3">
                            <a href="{{ route('ucp.gameaccount') }}" 
                               class="px-5 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-lg hover:from-gray-500 hover:to-gray-600 transition-all duration-300 font-medium shadow-lg">
                                <i class="fas fa-arrow-left mr-2"></i> Volver
                            </a>
                            <button class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-500 hover:to-red-600 transition-all duration-300 font-medium shadow-lg">
                                <i class="fas fa-trash mr-2"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Account Status Card -->
                <div class="bg-gray-800/30 backdrop-blur-sm rounded-xl border border-gray-800 p-6 shadow-xl">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center p-4 bg-green-900/20 rounded-full mb-3 border border-green-800/30">
                                <i class="fas fa-check-circle text-2xl text-green-400"></i>
                            </div>
                            <h3 class="text-sm font-medium text-gray-300 mb-1">Estado</h3>
                            <p class="text-green-400 font-semibold">Activa</p>
                        </div>
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center p-4 bg-blue-900/20 rounded-full mb-3 border border-blue-800/30">
                                <i class="fas fa-server text-2xl text-blue-400"></i>
                            </div>
                            <h3 class="text-sm font-medium text-gray-300 mb-1">Realm</h3>
                            <p class="text-blue-400 font-semibold">{{ $account->realm->name ?? 'N/A' }}</p>
                        </div>
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center p-4 bg-purple-900/20 rounded-full mb-3 border border-purple-800/30">
                                <i class="fas fa-calendar text-2xl text-purple-400"></i>
                            </div>
                            <h3 class="text-sm font-medium text-gray-300 mb-1">Creada</h3>
                            <p class="text-purple-400 font-semibold">{{ $account->created_at instanceof \DateTime ? $account->created_at->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center p-4 bg-yellow-900/20 rounded-full mb-3 border border-yellow-800/30">
                                <i class="fas fa-clock text-2xl text-yellow-400"></i>
                            </div>
                            <h3 class="text-sm font-medium text-gray-300 mb-1">Último Acceso</h3>
                            <p class="text-yellow-400 font-semibold">{{ $account->last_login instanceof \DateTime ? $account->last_login->format('d/m/Y') : 'Nunca' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="bg-gray-800/30 backdrop-blur-sm rounded-xl border border-gray-800 shadow-xl" x-data="{ activeTab: 'info' }">
                    <div class="border-b border-gray-700">
                        <nav class="flex space-x-8 px-6" aria-label="Tabs">
                            <button @click="activeTab = 'info'" 
                                    :class="activeTab === 'info' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                <i class="fas fa-info-circle mr-2"></i>Información
                            </button>
                            <button @click="activeTab = 'characters'" 
                                    :class="activeTab === 'characters' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                <i class="fas fa-users mr-2"></i>Personajes
                            </button>
                            <button @click="activeTab = 'security'" 
                                    :class="activeTab === 'security' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                <i class="fas fa-shield-alt mr-2"></i>Seguridad
                            </button>
                            <button @click="activeTab = 'settings'" 
                                    :class="activeTab === 'settings' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                <i class="fas fa-cog mr-2"></i>Configuración
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        <!-- Information Tab -->
                        <div x-show="activeTab === 'info'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Account Details -->
                                <div class="space-y-6">
                                    <h3 class="text-lg font-semibold text-white mb-4">
                                        <i class="fas fa-user text-indigo-400 mr-2"></i>Detalles de la Cuenta
                                    </h3>
                                    
                                    <div class="bg-gray-800/50 rounded-lg p-4 border border-gray-700">
                                        <label class="block text-sm font-medium text-gray-300 mb-2">Nombre de Usuario</label>
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-100 font-mono">{{ $account->username ?? 'N/A' }}</span>
                                            <span class="px-2 py-1 bg-gray-700 text-gray-300 text-xs rounded">No editable</span>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-800/50 rounded-lg p-4 border border-gray-700">
                                        <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                                        <div class="flex items-center space-x-3">
                                            <input type="email" value="{{ $account->email ?? '' }}" 
                                                   class="flex-1 px-3 py-2 bg-gray-800/70 border border-gray-600 rounded text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500 transition-colors">
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-800/50 rounded-lg p-4 border border-gray-700">
                                        <label class="block text-sm font-medium text-gray-300 mb-2">Expansión</label>
                                        <select class="w-full px-3 py-2 bg-gray-800/70 border border-gray-600 rounded text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            <option value="classic">Classic</option>
                                            <option value="tbc">The Burning Crusade</option>
                                            <option value="wotlk">Wrath of the Lich King</option>
                                            <option value="cata">Cataclysm</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Account Statistics -->
                                <div class="space-y-6">
                                    <h3 class="text-lg font-semibold text-white mb-4">
                                        <i class="fas fa-chart-bar text-indigo-400 mr-2"></i>Estadísticas
                                    </h3>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-gray-800/50 rounded-lg p-4 border border-gray-700 text-center">
                                            <div class="text-2xl font-bold text-blue-400 mb-1">{{ $account->characters_count ?? 0 }}</div>
                                            <div class="text-sm text-gray-400">Personajes</div>
                                        </div>
                                        <div class="bg-gray-800/50 rounded-lg p-4 border border-gray-700 text-center">
                                            <div class="text-2xl font-bold text-green-400 mb-1">{{ $account->total_playtime ?? '0h' }}</div>
                                            <div class="text-sm text-gray-400">Tiempo Jugado</div>
                                        </div>
                                        <div class="bg-gray-800/50 rounded-lg p-4 border border-gray-700 text-center">
                                            <div class="text-2xl font-bold text-purple-400 mb-1">{{ $account->gm_level ?? 0 }}</div>
                                            <div class="text-sm text-gray-400">Nivel GM</div>
                                        </div>
                                        <div class="bg-gray-800/50 rounded-lg p-4 border border-gray-700 text-center">
                                            <div class="text-2xl font-bold text-yellow-400 mb-1">{{ $account->failed_logins ?? 0 }}</div>
                                            <div class="text-sm text-gray-400">Intentos Fallidos</div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-800/50 rounded-lg p-4 border border-gray-700">
                                        <h4 class="text-sm font-medium text-gray-300 mb-3">Actividad Reciente</h4>
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-400">Último login:</span>
                                                <span class="text-gray-300"></span>
                                            </div>
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-400">IP del último login:</span>
                                                <span class="text-gray-300 font-mono">{{ $account->last_ip ?? 'N/A' }}</span>
                                            </div>
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-400">Estado de la cuenta:</span>
                                                <span class="text-green-400"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Characters Tab -->
                        <div x-show="activeTab === 'characters'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="space-y-6">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-white">
                                        <i class="fas fa-users text-indigo-400 mr-2"></i>Personajes de la Cuenta
                                    </h3>
                                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 transition-colors">
                                        <i class="fas fa-sync mr-2"></i>Actualizar
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @forelse($account->characters ?? [] as $character)
                                    <div class="bg-gray-800/50 rounded-lg p-6 border border-gray-700 hover:border-indigo-500 transition-colors">
                                        <div class="flex items-center space-x-4 mb-4">
                                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-white font-semibold">{{ $character->name }}</h4>
                                                <p class="text-gray-400 text-sm">{{ $character->class }} {{ $character->race }}</p>
                                            </div>
                                        </div>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-400">Nivel:</span>
                                                <span class="text-white font-medium">{{ $character->level }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-400">Zona:</span>
                                                <span class="text-white">{{ $character->zone ?? 'Desconocida' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-400">Tiempo jugado:</span>
                                                <span class="text-white">{{ $character->totaltime ?? '0h' }}</span>
                                            </div>
                                        </div>
                                        <div class="mt-4 flex space-x-2">
                                            <button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-500 transition-colors">
                                                <i class="fas fa-eye mr-1"></i>Ver
                                            </button>
                                            <button class="flex-1 px-3 py-2 bg-yellow-600 text-white rounded text-sm hover:bg-yellow-500 transition-colors">
                                                <i class="fas fa-edit mr-1"></i>Editar
                                            </button>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-span-full text-center py-12">
                                        <div class="inline-flex items-center justify-center p-6 bg-gray-800/50 rounded-full mb-4">
                                            <i class="fas fa-user-slash text-4xl text-gray-500"></i>
                                        </div>
                                        <h4 class="text-lg font-medium text-gray-400 mb-2">No hay personajes</h4>
                                        <p class="text-gray-500">Esta cuenta no tiene personajes creados aún.</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="space-y-8">
                                <h3 class="text-lg font-semibold text-white">
                                    <i class="fas fa-shield-alt text-indigo-400 mr-2"></i>Configuración de Seguridad
                                </h3>
                                
                                <!-- Change Password -->
                                <div class="bg-gray-800/50 rounded-lg p-6 border border-gray-700">
                                    <h4 class="text-white font-medium mb-4">
                                        <i class="fas fa-key text-yellow-400 mr-2"></i>Cambiar Contraseña
                                    </h4>
                                    <form class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Contraseña Actual</label>
                                            <input type="password" class="w-full px-3 py-2 bg-gray-800/70 border border-gray-600 rounded text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Nueva Contraseña</label>
                                            <input type="password" class="w-full px-3 py-2 bg-gray-800/70 border border-gray-600 rounded text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Confirmar Nueva Contraseña</label>
                                            <input type="password" class="w-full px-3 py-2 bg-gray-800/70 border border-gray-600 rounded text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        </div>
                                        <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-500 transition-colors">
                                            <i class="fas fa-save mr-2"></i>Cambiar Contraseña
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Account Lock/Unlock -->
                                <div class="bg-gray-800/50 rounded-lg p-6 border border-gray-700">
                                    <h4 class="text-white font-medium mb-4">
                                        <i class="fas fa-lock text-red-400 mr-2"></i>Bloqueo de Cuenta
                                    </h4>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-gray-300 mb-2">Estado actual: 
                                                <span class="{{ isset($account->locked) && $account->locked ? 'text-red-400' : 'text-green-400' }} font-medium">
                                                    {{ isset($account->locked) && $account->locked ? 'Bloqueada' : 'Activa' }}
                                                </span>
                                            </p>
                                            <p class="text-gray-500 text-sm">Bloquear la cuenta impedirá el acceso al juego</p>
                                        </div>
                                        <button class="px-6 py-2 {{ isset($account->locked) && $account->locked ? 'bg-green-600 hover:bg-green-500' : 'bg-red-600 hover:bg-red-500' }} text-white rounded transition-colors">
                                            <i class="fas fa-{{ isset($account->locked) && $account->locked ? 'unlock' : 'lock' }} mr-2"></i>
                                            {{ isset($account->locked) && $account->locked ? 'Desbloquear' : 'Bloquear' }}
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Login History -->
                                <div class="bg-gray-800/50 rounded-lg p-6 border border-gray-700">
                                    <h4 class="text-white font-medium mb-4">
                                        <i class="fas fa-history text-blue-400 mr-2"></i>Historial de Accesos
                                    </h4>
                                    <div class="space-y-3">
                                        @forelse($account->loginHistory ?? [] as $login)
                                        <div class="flex items-center justify-between py-2 border-b border-gray-700 last:border-b-0">
                                            <div>
                                                <span class="text-gray-300">{{ $login->created_at->format('d/m/Y H:i:s') }}</span>
                                                <span class="text-gray-500 ml-2">desde {{ $login->ip }}</span>
                                            </div>
                                            <span class="px-2 py-1 text-xs rounded {{ $login->success ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                                                {{ $login->success ? 'Exitoso' : 'Fallido' }}
                                            </span>
                                        </div>
                                        @empty
                                        <p class="text-gray-500 text-center py-4">No hay historial de accesos disponible</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Settings Tab -->
                        <div x-show="activeTab === 'settings'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="space-y-8">
                                <h3 class="text-lg font-semibold text-white">
                                    <i class="fas fa-cog text-indigo-400 mr-2"></i>Configuración Avanzada
                                </h3>
                                
                                <!-- GM Level -->
                                <div class="bg-gray-800/50 rounded-lg p-6 border border-gray-700">
                                    <h4 class="text-white font-medium mb-4">
                                        <i class="fas fa-crown text-yellow-400 mr-2"></i>Nivel de Game Master
                                    </h4>
                                    <div class="flex items-center space-x-4">
                                        <select class="flex-1 px-3 py-2 bg-gray-800/70 border border-gray-600 rounded text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            <option value="0" {{ ($account->gm_level ?? 0) == 0 ? 'selected' : '' }}>0 - Jugador Normal</option>
                                            <option value="1" {{ ($account->gm_level ?? 0) == 1 ? 'selected' : '' }}>1 - Moderador</option>
                                            <option value="2" {{ ($account->gm_level ?? 0) == 2 ? 'selected' : '' }}>2 - Game Master</option>
                                            <option value="3" {{ ($account->gm_level ?? 0) == 3 ? 'selected' : '' }}>3 - Administrador</option>
                                        </select>
                                        <button class="px-6 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-500 transition-colors">
                                            <i class="fas fa-save mr-2"></i>Actualizar
                                        </button>
                                    </div>
                                    <p class="text-gray-500 text-sm mt-2">Cambiar el nivel de permisos de la cuenta</p>
                                </div>
                                
                                <!-- Account Flags -->
                                <div class="bg-gray-800/50 rounded-lg p-6 border border-gray-700">
                                    <h4 class="text-white font-medium mb-4">
                                        <i class="fas fa-flag text-purple-400 mr-2"></i>Banderas de Cuenta
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <label class="flex items-center space-x-3">
                                            <input type="checkbox" class="w-4 h-4 text-indigo-600 bg-gray-800 border-gray-600 rounded focus:ring-indigo-500">
                                            <span class="text-gray-300">Cuenta VIP</span>
                                        </label>
                                        <label class="flex items-center space-x-3">
                                            <input type="checkbox" class="w-4 h-4 text-indigo-600 bg-gray-800 border-gray-600 rounded focus:ring-indigo-500">
                                            <span class="text-gray-300">Beta Tester</span>
                                        </label>
                                        <label class="flex items-center space-x-3">
                                            <input type="checkbox" class="w-4 h-4 text-indigo-600 bg-gray-800 border-gray-600 rounded focus:ring-indigo-500">
                                            <span class="text-gray-300">Acceso Prioritario</span>
                                        </label>
                                        <label class="flex items-center space-x-3">
                                            <input type="checkbox" class="w-4 h-4 text-indigo-600 bg-gray-800 border-gray-600 rounded focus:ring-indigo-500">
                                            <span class="text-gray-300">Cuenta Premium</span>
                                        </label>
                                    </div>
                                    <button class="mt-4 px-6 py-2 bg-purple-600 text-white rounded hover:bg-purple-500 transition-colors">
                                        <i class="fas fa-save mr-2"></i>Guardar Banderas
                                    </button>
                                </div>
                                
                                <!-- Danger Zone -->
                                <div class="bg-red-900/20 border border-red-800/50 rounded-lg p-6">
                                    <h4 class="text-red-400 font-medium mb-4">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Zona de Peligro
                                    </h4>
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-gray-300 font-medium">Resetear Cuenta</p>
                                                <p class="text-gray-500 text-sm">Elimina todos los personajes y resetea la cuenta</p>
                                            </div>
                                            <button class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-500 transition-colors">
                                                <i class="fas fa-redo mr-2"></i>Resetear
                                            </button>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-gray-300 font-medium">Eliminar Cuenta</p>
                                                <p class="text-gray-500 text-sm">Elimina permanentemente la cuenta y todos sus datos</p>
                                            </div>
                                            <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-500 transition-colors">
                                                <i class="fas fa-trash mr-2"></i>Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection