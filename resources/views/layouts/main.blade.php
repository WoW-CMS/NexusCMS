<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ settings('site_name') }} - @yield('title', 'World of Warcraft Private Server')</title>
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
                        <div class="rounded-lg flex items-center justify-center text-2xl font-bold shadow-lg shadow-red-900/50">
                            {{ settings('site_name') }}
                        </div>
                    </div>
                    <div class="hidden md:flex gap-6">
                        @foreach(menu_items('web') as $item)
                            @if(!empty($item['children']))
 <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                    <button class="flex items-center gap-1 text-slate-300 hover:text-white transition-colors duration-200 font-medium">
                                        {{ $item['label'] ?? 'MENU' }}
                                        <i class="fas fa-chevron-down text-xs opacity-70"></i>
                                    </button>
                                    <div x-show="open"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="opacity-0 transform scale-95"
                                         x-transition:enter-end="opacity-100 transform scale-100"
                                         class="absolute left-0 mt-2 w-48 bg-slate-800 border border-slate-700 rounded-lg shadow-xl py-1 z-50">
                                        @foreach($item['children'] as $child)
                                            <a href="{{ menu_item_href($child) }}"
                                               class="block px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-700 transition-colors">
                                                {{ $child['label'] ?? '' }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ menu_item_href($item) }}" class="text-slate-300 hover:text-white transition-colors duration-200 font-medium">{{ $item['label'] ?? 'MENU' }}</a>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        <div class="hidden md:flex items-center gap-3">
                            <a href="{{ route('ucp.dashboard') }}" class="text-slate-300 hover:text-white transition-colors duration-200 font-medium">
                            <span class="text-slate-300 text-sm">{{ Auth::user()->name }}</span>
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-slate-300 hover:text-white transition-colors duration-200 font-medium">Logout</button>
                            </form>
                        </div>
                    @else
                        <div class="hidden md:flex gap-3">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-slate-300 hover:text-white transition-colors duration-200 font-medium">Login</a>
                            <a href="{{ route('register') }}" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 font-medium shadow-lg shadow-blue-900/30">Register</a>
                        </div>
                    @endauth
                    <button id="mobile-menu-button" class="md:hidden lg:hidden text-slate-300 hover:text-white focus:outline-none focus:text-white">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4">
                <div class="flex flex-col gap-2">
                    @foreach(menu_items('web') as $item)
                        @if(!empty($item['children']))
                            <div class="pl-4 border-l-2 border-slate-700">
                                <p class="text-slate-500 text-xs uppercase tracking-wider py-1">{{ $item['label'] }}</p>
                                @foreach($item['children'] as $child)
                                    <a href="{{ menu_item_href($child) }}" class="text-slate-300 hover:text-white transition-colors duration-200 font-medium py-2 block">{{ $child['label'] ?? '' }}</a>
                                @endforeach
                            </div>
                        @else
                            <a href="{{ menu_item_href($item) }}" class="text-slate-300 hover:text-white transition-colors duration-200 font-medium py-2">{{ $item['label'] ?? 'MENU' }}</a>
                        @endif
                    @endforeach
                    @auth
                        <div class="flex flex-col gap-2 pt-2">
                            <a href="{{ route('ucp.dashboard') }}" class="text-slate-300 hover:text-white transition-colors duration-200 font-medium py-2">
                                <span class="text-slate-300 text-sm">{{ Auth::user()->name }}</span>
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-slate-300 hover:text-white transition-colors duration-200 font-medium">Logout</button>
                            </form>
                        </div>
                    @else
                        <div class="flex gap-3 pt-2">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-slate-300 hover:text-white transition-colors duration-200 font-medium">Login</a>
                            <a href="{{ route('register') }}" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 font-medium shadow-lg shadow-blue-900/30">Register</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>

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
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('news') }}" class="hover:text-white transition-colors">News</a></li>
                        <li><a href="{{ route('howtoplay') }}" class="hover:text-white transition-colors">Download</a></li>
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
