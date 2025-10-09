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
                                Crear Nueva Cuenta
                            </h2>                            
                            <p class="text-gray-400 text-sm">Completa todos los campos para crear tu nueva cuenta de juego</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="{{ route('ucp.gameaccount') }}" 
                               class="px-5 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-lg hover:from-gray-500 hover:to-gray-600 transition-all duration-300 font-medium shadow-lg">
                                <i class="fas fa-arrow-left mr-2"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Create Account Form -->
                <div class="bg-gray-800/30 backdrop-blur-sm rounded-xl border border-gray-800 p-8 shadow-xl">
                    <div class="max-w-2xl mx-auto">
                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="mb-6 bg-red-900/20 border border-red-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-red-400 text-xl mr-3 mt-0.5"></i>
                                    <div class="flex-1">
                                        <h3 class="text-red-400 font-medium mb-2">Se encontraron los siguientes errores:</h3>
                                        <ul class="text-sm text-red-300 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li class="flex items-start">
                                                    <span class="text-red-400 mr-2">•</span>
                                                    <span>{{ $error }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Success Message -->
                        @if (session('success'))
                            <div class="mb-6 bg-green-900/20 border border-green-800 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-400 text-xl mr-3"></i>
                                    <div>
                                        <span class="text-green-400 font-medium">¡Éxito!</span>
                                        <p class="text-sm text-green-300">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Error Message -->
                        @if (session('error'))
                            <div class="mb-6 bg-red-900/20 border border-red-800 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-circle text-red-400 text-xl mr-3"></i>
                                    <div>
                                        <span class="text-red-400 font-medium">Error</span>
                                        <p class="text-sm text-red-300">{{ session('error') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form class="space-y-6" method="POST" action="{{ route('ucp.gameaccount.store') }}">
                            @csrf

                            <!-- Expansion Selection -->
                            <div class="bg-gray-800/50 rounded-lg p-6 border border-gray-700">
                                <label class="block text-sm font-medium text-gray-300 mb-4">
                                    <i class="fas fa-globe text-indigo-400 mr-2"></i> Seleciona el realm
                                </label>
                                <div class="space-y-4">
                                    @foreach($realms ?? [] as $realm)
                                        <label class="relative flex items-start p-6 bg-gradient-to-br from-gray-800/80 via-gray-800/60 to-gray-800/80 border-2 border-gray-700 rounded-xl cursor-pointer hover:border-indigo-500 transition-all duration-300 group shadow-lg realm-option">
                                            <input type="radio" name="realm" value="{{ $realm->id }}" {{ old('realm') == $realm->id ? 'checked' : '' }} class="sr-only realm-radio" required>
                                            <div class="flex-shrink-0 w-6 h-6 border-2 border-gray-500 rounded-full mt-1 flex items-center justify-center group-hover:border-indigo-400 transition-colors radio-indicator">
                                                <div class="w-3 h-3 bg-indigo-500 rounded-full opacity-0 transition-opacity radio-dot"></div>
                                            </div>
                                            <div class="ml-4 flex-1 min-w-0">
                                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                                        <span class="text-lg text-gray-100 font-semibold truncate">{{ $realm->name }}</span>

                                                    </div>
                                                    <div class="flex items-center gap-2 text-sm font-medium text-emerald-400">
                                                        <span class="text-sm font-medium text-gray-200 truncate">
                                                            {{ App\Helpers\RealmHelper::getWoWConstant('expansion', $realm->expansion) }}
                                                        </span>
                                                        
                                                        <div class="hidden sm:block h-8 w-px bg-gray-700"></div>

                                                        <span class="px-3 py-1 text-xs font-medium bg-indigo-500/20 text-indigo-300 rounded-full border border-indigo-500/20 w-fit">
                                                            {{ App\Helpers\RealmHelper::getWoWConstant('version', $realm->expansion) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @if(empty($realms))
                                <div class="flex items-center p-6 bg-red-900/20 border border-red-800 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-triangle text-red-400 text-xl mr-4"></i>
                                        <div>
                                            <span class="text-red-400 font-medium">No hay reinos disponibles</span>
                                            <p class="text-sm text-red-300/70">Actualmente no hay ningún reino configurado donde puedas crear una cuenta.</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @error('realm')
                                    <p class="text-xs text-red-400 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            @if(!empty($realms))
                            <!-- Username -->
                            <div class="bg-gray-800/50 rounded-lg p-6 border border-gray-700">
                                <label class="block text-sm font-medium text-gray-300 mb-3">
                                    <i class="fas fa-user text-indigo-400 mr-2"></i>Nombre de Usuario
                                </label>
                                <input type="text" name="username" value="{{ old('username') }}" required 
                                       placeholder="Ingresa el nombre de usuario"
                                       class="w-full px-4 py-3 bg-gray-800/70 border {{ $errors->has('username') ? 'border-red-500' : 'border-gray-600' }} rounded-lg text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                @error('username')
                                    <p class="text-xs text-red-400 mt-2">{{ $message }}</p>
                                @else
                                    <p class="text-xs text-gray-500 mt-2">Mínimo 3 caracteres, máximo 12. Solo letras y números</p>
                                @enderror
                            </div>
                            
                            <!-- Password -->
                            <div class="bg-gray-800/50 rounded-lg p-6 border border-gray-700">
                                <label class="block text-sm font-medium text-gray-300 mb-3">
                                    <i class="fas fa-lock text-indigo-400 mr-2"></i>Contraseña
                                </label>
                                <input type="password" name="password" required 
                                       placeholder="Ingresa la contraseña"
                                       class="w-full px-4 py-3 bg-gray-800/70 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-600' }} rounded-lg text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                @error('password')
                                    <p class="text-xs text-red-400 mt-2">{{ $message }}</p>
                                @else
                                    <p class="text-xs text-gray-500 mt-2">Mínimo 6 caracteres, se recomienda usar mayúsculas, minúsculas y números</p>
                                @enderror
                            </div>
                            
                            <!-- Confirm Password -->
                            <div class="bg-gray-800/50 rounded-lg p-6 border border-gray-700">
                                <label class="block text-sm font-medium text-gray-300 mb-3">
                                    <i class="fas fa-lock text-indigo-400 mr-2"></i>Confirmar Contraseña
                                </label>
                                <input type="password" name="password_confirmation" required 
                                       placeholder="Confirma la contraseña"
                                       class="w-full px-4 py-3 bg-gray-800/70 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-600' }} rounded-lg text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                @error('password_confirmation')
                                    <p class="text-xs text-red-400 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Email -->
                            <div class="bg-gray-800/50 rounded-lg p-6 border border-gray-700">
                                <label class="block text-sm font-medium text-gray-300 mb-3">
                                    <i class="fas fa-envelope text-indigo-400 mr-2"></i>Email
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       placeholder="youremail@tld.com"
                                       class="w-full px-4 py-3 bg-gray-800/70 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-600' }} rounded-lg text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                @error('email')
                                    <p class="text-xs text-red-400 mt-2">{{ $message }}</p>
                                @else
                                    <p class="text-xs text-gray-500 mt-2">Para recuperación de cuenta y notificaciones importantes</p>
                                @enderror
                            </div>


                            <!-- Terms and Conditions -->
                            <div class="bg-gray-800/50 rounded-lg p-6 border border-gray-700">
                                <label class="flex items-start space-x-3 cursor-pointer">
                                    <input type="checkbox" name="terms" required 
                                           class="mt-1 w-4 h-4 text-indigo-600 bg-gray-800 border-gray-600 rounded focus:ring-indigo-500 focus:ring-2">
                                    <div class="text-sm">
                                        <span class="text-gray-300">Acepto los </span>
                                        <a href="#" class="text-indigo-400 hover:text-indigo-300 underline">términos y condiciones</a>
                                        <span class="text-gray-300"> y las </span>
                                        <a href="#" class="text-indigo-400 hover:text-indigo-300 underline">políticas de privacidad</a>
                                        <span class="text-gray-300"> del servidor.</span>
                                    </div>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-between pt-6">
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    La cuenta será vinculada automáticamente a tu perfil
                                </div>
                                <button type="submit" 
                                        class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-500 hover:to-purple-500 transition-all duration-300 font-medium shadow-lg shadow-indigo-600/20">
                                    <i class="fas fa-plus mr-2"></i>Crear Cuenta
                                </button>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Radio button selection handling
document.addEventListener('DOMContentLoaded', function() {
    const radioInputs = document.querySelectorAll('.realm-radio');
    
    radioInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Remove selected state from all radio buttons in the same group
            const groupName = this.name;
            const groupInputs = document.querySelectorAll(`input[name="${groupName}"]`);
            
            groupInputs.forEach(groupInput => {
                const label = groupInput.closest('.realm-option');
                const indicator = label.querySelector('.radio-indicator');
                const dot = label.querySelector('.radio-dot');
                
                if (groupInput === this) {
                    // Selected state
                    dot.classList.remove('opacity-0');
                    dot.classList.add('opacity-100');
                    indicator.classList.add('border-indigo-400');
                    indicator.classList.remove('border-gray-500');
                    label.classList.add('border-indigo-500', 'bg-indigo-900/20');
                    label.classList.remove('border-gray-700');
                } else {
                    // Unselected state
                    dot.classList.remove('opacity-100');
                    dot.classList.add('opacity-0');
                    indicator.classList.remove('border-indigo-400');
                    indicator.classList.add('border-gray-500');
                    label.classList.remove('border-indigo-500', 'bg-indigo-900/20');
                    label.classList.add('border-gray-700');
                }
            });
        });
    });
});
</script>
@endsection