<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'NexusCMS') }} - @yield('title', 'World of Warcraft Private Server')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-slate-950 text-slate-100 font-sans overflow-x-hidden">
    <!-- Navigation -->
    <nav class="fixed w-full top-0 z-50 bg-slate-900/80 backdrop-blur-lg border-b border-slate-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex items-center justify-center text-2xl font-bold shadow-lg shadow-red-900/50">
                            ?
                        </div>
                    </div>
                    <div class="hidden md:flex gap-6">
                        <a href="#" class="text-slate-300 hover:text-white transition-colors duration-200 font-medium">HOME</a>
                        <a href="#" class="text-slate-300 hover:text-white transition-colors duration-200 font-medium">NEWS</a>
                        <a href="#" class="text-slate-300 hover:text-white transition-colors duration-200 font-medium">HOW TO PLAY</a>
                        <a href="#" class="text-slate-300 hover:text-white transition-colors duration-200 font-medium">FORUMS</a>
                        <a href="#" class="text-slate-300 hover:text-white transition-colors duration-200 font-medium">ARMORY</a>
                        <a href="#" class="text-slate-300 hover:text-white transition-colors duration-200 font-medium">DONATE</a>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button class="px-4 py-2 text-slate-300 hover:text-white transition-colors duration-200 font-medium">Login</button>
                    <button class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 font-medium shadow-lg shadow-blue-900/30">Register</button>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    @yield('modals')
    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex items-center justify-center text-2xl font-bold">
                            ?
                        </div>
                        <span class="text-xl font-bold text-white">NexusCMS</span>
                    </div>
                    <p class="text-slate-400 text-sm">The ultimate World of Warcraft private server experience.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-slate-400 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">News</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Forums</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Download</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Community</h4>
                    <ul class="space-y-2 text-slate-400 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Discord</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Support</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Rules</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2 text-slate-400 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 text-center text-slate-400 text-sm">
                <p>&copy; 2025 NexusCMS. All rights reserved. World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc.</p>
            </div>
        </div>
    </footer>
    @yield('scripts')
</body>
</html>
