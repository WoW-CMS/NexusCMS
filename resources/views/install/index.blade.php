<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Installer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="bg-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 shadow-lg">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 7.172V5L8 4z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Application Installer</h1>
            <p class="text-gray-600">Configura tu aplicación en unos simples pasos</p>
        </div>

        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-2 md:space-x-4">
                @foreach(range(1,4) as $step)
                <div class="flex items-center">
                    <div id="step{{ $step }}-indicator" class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold text-sm">{{ $step }}</div>
                    <span id="step{{ $step }}-text" class="ml-2 text-sm font-medium text-gray-400 hidden md:block">
                        @switch($step)
                            @case(1) App Config @break
                            @case(2) Database @break
                            @case(3) Terms @break
                            @case(4) Install @break
                        @endswitch
                    </span>
                </div>
                @if($step < 4)
                <div class="w-8 md:w-16 h-0.5 bg-gray-300" id="progress-bar-{{ $step }}"></div>
                @endif
                @endforeach
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form action="{{ route('install.run') }}" method="POST" id="installerForm">
                @csrf
                
                <!-- Step 1: Application Configuration -->
                <div id="step1" class="step-content">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Configuración de la Aplicación</h2>
                        <p class="text-gray-600 text-sm">Configura los datos básicos de tu aplicación</p>
                    </div>

                    <div class="space-y-6">
                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Aplicación</label>
                            <input type="text" name="app_name" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200" placeholder="Mi Aplicación Increíble">
                        </div>

                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">URL de la Aplicación</label>
                            <input type="url" name="app_url" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200" placeholder="https://miapp.com">
                        </div>

                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Idioma</label>
                            <select name="locale" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                <option value="en">English</option>
                                <option value="es">Español</option>
                                <option value="fr">Français</option>
                                <option value="de">Deutsch</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Database Configuration -->
                <div id="step2" class="step-content hidden">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Configuración de Base de Datos</h2>
                        <p class="text-gray-600 text-sm">Configura la conexión a tu base de datos MySQL</p>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Host</label>
                                <input type="text" name="db_host" value="127.0.0.1" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                            </div>

                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Puerto</label>
                                <input type="number" name="db_port" value="3306" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Base de Datos</label>
                            <input type="text" name="db_name" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200" placeholder="mi_aplicacion">
                        </div>

                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Usuario</label>
                            <input type="text" name="db_username" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200" placeholder="root">
                        </div>

                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                            <input type="password" name="db_password" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200" placeholder="••••••••">
                        </div>

                        <!-- Test Connection Button -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-blue-800">Puedes probar la conexión antes de continuar</span>
                                <button type="button" id="testConnection" class="px-3 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 transition-colors duration-200">Probar Conexión</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Terms -->
                <div id="step3" class="step-content hidden">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Términos y Condiciones</h2>
                        <p class="text-gray-600 text-sm">Por favor acepta los términos antes de continuar</p>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input id="agree_terms" name="agree_terms" type="checkbox" required class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="agree_terms" class="ml-2 text-sm font-medium text-gray-900">Acepto los términos y condiciones *</label>
                        </div>
                        <div class="flex items-center">
                            <input id="agree_privacy" name="agree_privacy" type="checkbox" required class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="agree_privacy" class="ml-2 text-sm font-medium text-gray-900">Acepto la política de privacidad *</label>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Confirmation -->
                <div id="step4" class="step-content hidden text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">¡Listo para Instalar!</h2>
                    <p class="text-gray-600 mb-6">Revisa la configuración y procede</p>
                </div>

                <!-- Hidden inputs -->
                <input type="hidden" name="db_connection" value="mysql">

                <!-- Navigation Buttons -->
                <div class="flex justify-between pt-6 border-t border-gray-200">
                    <button type="button" id="prevBtn" class="px-6 py-3 text-gray-600 hover:text-gray-800 font-medium transition-colors duration-200 hidden">← Anterior</button>
                    <div class="flex-1"></div>
                    <button type="button" id="nextBtn" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors duration-200">Siguiente →</button>
                    <button type="submit" id="installBtn" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors duration-200 hidden">Instalar ✅</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stepper Script -->
    <script>
        let currentStep = 1;
        const totalSteps = 4;

        const showStep = (step) => {
            document.querySelectorAll('.step-content').forEach((el, i) => {
                el.classList.add('hidden');
            });
            document.getElementById('step'+step).classList.remove('hidden');

            document.getElementById('prevBtn').style.display = step === 1 ? 'none' : 'inline-flex';
            document.getElementById('nextBtn').style.display = step === totalSteps ? 'none' : 'inline-flex';
            document.getElementById('installBtn').style.display = step === totalSteps ? 'inline-flex' : 'none';
        }

        document.getElementById('nextBtn').addEventListener('click', () => {
            if(currentStep < totalSteps){
                currentStep++;
                showStep(currentStep);
            }
        });

        document.getElementById('prevBtn').addEventListener('click', () => {
            if(currentStep > 1){
                currentStep--;
                showStep(currentStep);
            }
        });

        // Test DB Connection
        document.getElementById('testConnection').addEventListener('click', function () {
            let form = document.getElementById('installerForm');
            let formData = new FormData(form);

            fetch('{{ route("install.testDb") }}', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: formData
            })
            .then(res => res.json())
            .then(data => alert(data.message))
            .catch(err => alert('Error al probar la conexión'));
        });

        showStep(currentStep);
    </script>
</body>
</html>
