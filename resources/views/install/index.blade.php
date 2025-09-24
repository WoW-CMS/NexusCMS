<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexusCMS Installer</title>
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
            <h1 class="text-3xl font-bold text-gray-900 mb-2">NexusCMS Installer</h1>
            <p class="text-gray-600">Configure the application settings and database connection.</p>
        </div>

        @php
            $currentStep = $currentStep ?? 0;
        @endphp

        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-2 md:space-x-4">
                @foreach(range(0,4) as $step)
                <div class="flex items-center">
                    <div 
                        id="step{{ $step }}-indicator" 
                        class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm leading-none flex-shrink-0
                            {{ $currentStep == $step ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600' }}">
                        {{ $step == 0 ? 'EULA' : $step }}
                    </div>
                    <span 
                        id="step{{ $step }}-text" 
                        class="ml-2 text-sm font-medium hidden md:block
                            {{ $currentStep == $step ? 'text-blue-600' : 'text-gray-400' }}">
                        @switch($step)
                            @case(0) EULA @break
                            @case(1) Application Config @break
                            @case(2) Database Config @break
                            @case(3) Admin account @break
                            @case(4) Installation @break
                        @endswitch
                    </span>
                </div>
                @if($step < 5)
                <div 
                    class="w-8 md:w-16 h-0.5 
                        {{ $currentStep > $step ? 'bg-blue-500' : 'bg-gray-300' }}" 
                    id="progress-bar-{{ $step }}">
                </div>
                @endif
                @endforeach
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form action="{{ route('install.run') }}" method="POST" id="installerForm">
                @csrf

                <!-- Step 0: EULA -->
                <div id="step0" class="step-content hidden">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">End User License Agreement (EULA)</h2>
                        <p class="text-gray-600 text-sm mb-4">Please read and accept the EULA to continue the installation.</p>
                        <div class="p-4 h-64 overflow-y-auto border border-gray-200 rounded-lg bg-gray-50 mb-4 text-sm text-gray-700">
                            <p><strong>Last Updated: September 24, 2025</strong></p>
                            <p><strong>1. Acceptance of Terms:</strong> By installing, copying, accessing, or using NexusCMS (“the Software”), you agree to be bound by this End User License Agreement (“EULA”). If you do not agree to these terms, do not install or use the Software.</p>
                            <p><strong>2. License Grant:</strong> Subject to the terms of this EULA, NexusCMS grants you a non-exclusive, non-transferable, revocable license to use the Software for creating, managing, and maintaining websites or web applications. You may not redistribute, sublicense, sell, or modify the Software beyond what is permitted by the documentation.</p>
                            <p><strong>3. Intellectual Property:</strong> The Software and all its components are the property of NexusCMS and protected by copyright, trademark, and other intellectual property laws. This EULA does not transfer ownership to you.</p>
                            <p><strong>4. Restrictions:</strong> You may not reverse engineer, decompile, disassemble, remove proprietary notices, or use the Software for illegal activities.</p>
                            <p><strong>5. User Data and Privacy:</strong> The Software may collect technical information required for functionality and updates. Collected data is not shared with third parties without consent. You are responsible for complying with privacy laws.</p>
                            <p><strong>6. Updates and Upgrades:</strong> Updates or patches are subject to this EULA. NexusCMS is not obligated to provide updates or support.</p>
                            <p><strong>7. Warranty Disclaimer:</strong> The Software is provided “AS IS” without warranties of any kind, express or implied.</p>
                            <p><strong>8. Limitation of Liability:</strong> NexusCMS is not liable for any direct or indirect damages, loss of data, or business opportunities arising from use of the Software.</p>
                            <p><strong>9. Indemnification:</strong> You agree to indemnify and hold harmless NexusCMS from any claims arising from your use of the Software or violation of this EULA.</p>
                            <p><strong>10. Termination:</strong> This EULA terminates automatically upon violation of its terms. Upon termination, cease using the Software and destroy all copies.</p>
                            <p><strong>11. Governing Law:</strong> This EULA is governed by the laws of Spain, and disputes shall be resolved in competent courts in Spain.</p>
                            <p><strong>12. Miscellaneous:</strong> If any provision is unenforceable, the rest remain in effect. This EULA constitutes the entire agreement, and no waiver of any provision constitutes waiver of others.</p>
                        </div>
                        <div class="flex items-center">
                            <input id="agree_eula" name="agree_eula" type="checkbox" required class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="agree_eula" class="ml-2 text-sm font-medium text-gray-900"> I accept the EULA *</label>
                        </div>
                    </div>
                </div>

                <!-- Step 1: Application Configuration -->
                <div id="step1" class="step-content hidden">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Application Configuration</h2>
                        <p class="text-gray-600 text-sm">Configure the basic settings of your application</p>
                    </div>

                    <div class="space-y-6">
                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Application Name</label>
                            <input type="text" name="app_name" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200" placeholder="My Incredible Application">
                        </div>

                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Application URL</label>
                            <input type="url" name="app_url" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200" placeholder="https://myapp.com">
                        </div>

                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Application Language</label>
                            <select name="locale" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                <option value="en">English</option>
                                <option value="es">Spanish</option>
                                <option value="fr">French</option>
                                <option value="de">German</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Database Configuration -->
                <div id="step2" class="step-content hidden">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Database Configuration</h2>
                        <p class="text-gray-600 text-sm">Configure the connection to your MySQL database</p>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Database Host</label>
                                <input type="text" name="db_host" value="127.0.0.1" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                            </div>

                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Database Port</label>
                                <input type="number" name="db_port" value="3306" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Database Name</label>
                            <input type="text" name="db_name" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200" placeholder="my_application">
                        </div>

                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Database Username</label>
                            <input type="text" name="db_username" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200" placeholder="root">
                        </div>

                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Database Password</label>
                            <input type="password" name="db_password" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200" placeholder="••••••••">
                        </div>

                        <!-- Test Connection Button -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-blue-800">You can test the connection before continuing</span>
                                <button type="button" id="testConnection" class="px-3 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 transition-colors duration-200">Test Connection</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Admin Account -->
                <div id="step3" class="step-content hidden">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Admin Account</h2>
                        <p class="text-gray-600 text-sm">Create the main administrator account for your CMS.</p>
                    </div>

                    <div class="space-y-6">
                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Name</label>
                            <input type="text" name="admin_name" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                placeholder="John Doe">
                        </div>

                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Email</label>
                            <input type="email" name="admin_email" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                placeholder="admin@example.com">
                        </div>

                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" name="admin_password" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                placeholder="••••••••">
                        </div>

                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" name="admin_password_confirmation" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <!-- Step 4: Installation -->
                <div id="step4" class="step-content hidden text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Installation</h2>
                    <p class="text-gray-600 mb-6">Ready to run the installer. Click <strong>Install</strong> to finish setup.</p>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-700">Review your settings and click <strong>Install</strong> to proceed.</p>
                    </div>
                </div>

                <!-- Hidden inputs -->
                <input type="hidden" name="db_connection" value="mysql">

                <!-- Navigation Buttons -->
                <div class="flex justify-between pt-6 border-t border-gray-200">
                    <button type="button" id="prevBtn" class="px-6 py-3 text-gray-600 hover:text-gray-800 font-medium transition-colors duration-200 hidden">Previous ←</button>
                    <div class="flex-1"></div>
                    <button type="button" id="nextBtn" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors duration-200">Next →</button>
                    <button type="submit" id="installBtn" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors duration-200 hidden">Install</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stepper Script -->
    <script>
        let currentStep = Number(@json($currentStep ?? 0));

        const stepContents = Array.from(document.querySelectorAll('.step-content'));
        const indicators = Array.from(document.querySelectorAll('[id$="-indicator"]'));
        const progressBars = Array.from(document.querySelectorAll('[id^="progress-bar-"]'));
        const totalSteps = stepContents.length;

        const updateIndicators = (step) => {
            indicators.forEach((ind, i) => {
                const s = i;
                ind.classList.toggle('bg-blue-500', s === step);
                ind.classList.toggle('text-white', s === step);
                ind.classList.toggle('bg-gray-300', s !== step);
                ind.classList.toggle('text-gray-600', s !== step);

                const text = document.getElementById('step'+s+'-text');
                if (text) {
                    text.classList.toggle('text-blue-600', s === step);
                    text.classList.toggle('text-gray-400', s !== step);
                }
            });

            progressBars.forEach((bar, i) => {
                const s = i;
                bar.classList.toggle('bg-blue-500', step > s);
                bar.classList.toggle('bg-gray-300', step <= s);
            });
        };

        const showStep = (step) => {
            step = Math.max(0, Math.min(step, totalSteps - 1));
            stepContents.forEach(el => el.classList.add('hidden'));
            const content = document.getElementById('step' + step);
            if (content) content.classList.remove('hidden');

            document.getElementById('prevBtn').style.display = step === 0 ? 'none' : 'inline-flex';
            document.getElementById('nextBtn').style.display = step === totalSteps - 1 ? 'none' : 'inline-flex';
            document.getElementById('installBtn').style.display = step === totalSteps - 1 ? 'inline-flex' : 'none';

            updateIndicators(step);
            currentStep = step;
        };

        document.getElementById('nextBtn').addEventListener('click', () => {
            // Validate EULA on step0
            if(currentStep === 0) {
                const eulaChecked = document.getElementById('agree_eula').checked;
                if(!eulaChecked) {
                    alert('You must accept the EULA to continue.');
                    return;
                }
            }
            if (currentStep < totalSteps - 1) showStep(currentStep + 1);
        });

        document.getElementById('prevBtn').addEventListener('click', () => {
            if (currentStep > 0) showStep(currentStep - 1);
        });

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
            .catch(err => alert('Error testing the connection'));
        });

        document.addEventListener('DOMContentLoaded', () => {
            showStep(currentStep);
        });
    </script>
</body>
</html>
