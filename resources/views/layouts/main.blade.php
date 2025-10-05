<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'NexusCMS') }} - @yield('title', 'World of Warcraft Private Server')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-gray-800 bg-opacity-95 fixed w-full z-50 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <img class="h-12 w-auto transition-transform duration-300 hover:scale-110" src="https://wow.zamimg.com/images/wow/icons/large/inv_misc_questionmark.jpg" alt="Logo">
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-6">
                            <a href="{{ route('home') }}" class="nav-link @if(request()->routeIs('home')) bg-gray-900 text-white @else text-gray-300 hover:text-white @endif px-4 py-3 rounded-md text-sm font-medium uppercase tracking-wider">Home</a>
                            <a href="{{ route('news') }}" class="nav-link text-gray-300 hover:text-white px-4 py-3 rounded-md text-sm font-medium uppercase tracking-wider">News</a>
                            <a href="{{ route('howtoplay') }}" class="nav-link text-gray-300 hover:text-white px-4 py-3 rounded-md text-sm font-medium uppercase tracking-wider">How to Play</a>
                            <a href="{{ route('forums') }}" class="nav-link text-gray-300 hover:text-white px-4 py-3 rounded-md text-sm font-medium uppercase tracking-wider">Forums</a>
                            <a href="#" class="nav-link text-gray-300 hover:text-white px-4 py-3 rounded-md text-sm font-medium uppercase tracking-wider">Armory</a>
                            <a href="#" class="nav-link text-gray-300 hover:text-white px-4 py-3 rounded-md text-sm font-medium uppercase tracking-wider">Donate</a>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6 space-x-4">
                        @auth
                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                <button @click="open = !open" 
                                    class="flex items-center text-gray-300 hover:text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-300 hover:bg-gray-700"
                                    :aria-expanded="open">
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg class="ml-2 h-5 w-5 text-gray-400 transition-transform duration-200" 
                                         :class="{ 'transform rotate-180': open }"
                                         xmlns="http://www.w3.org/2000/svg" 
                                         viewBox="0 0 20 20" 
                                         fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-gray-800 ring-1 ring-black ring-opacity-5"
                                     style="display: none;">
                                    <div class="py-1">
                                        <a href="{{ route('ucp.dashboard') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                            Your Profile
                                        </a>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                            Account Settings
                                        </a>
                                        <a href="{{ route('ucp.gameaccount') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                            Your Game Accounts
                                        </a>
                                        <div class="border-t border-gray-700"></div>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                            Admin Control Panel
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md text-sm font-medium transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-300 hover:bg-gray-700">Login</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium transition-all duration-300 transform hover:scale-105 hover:shadow-lg">Register</a>
                        @endauth
                    </div>
                </div>
                <div class="-mr-2 flex md:hidden">
                    <button type="button" class="bg-gray-800 inline-flex items-center justify-center p-3 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition-colors duration-300">
                        <span class="sr-only">Open main menu</span>
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow relative">
        @yield('content')
    </main>

    @yield('modals')
    <!-- Footer -->
    <footer class="bg-gray-800 pt-16 pb-12 border-t border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="space-y-4">
                    <h3 class="text-white text-xl font-bold mb-6">NexusCMS</h3>
                    <p class="text-gray-400 leading-relaxed">The ultimate World of Warcraft private server experience with custom content and an amazing community.</p>
                    <div class="flex space-x-6 mt-6">
                        <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors duration-300">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-300">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-indigo-500 transition-colors duration-300">
                            <i class="fab fa-discord text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-red-500 transition-colors duration-300">
                            <i class="fab fa-youtube text-lg"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-white text-xl font-bold mb-6">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Home</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>News</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>How to Play</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Forums</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Armory</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white text-xl font-bold mb-6">Support</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center"><i class="fas fa-question-circle text-xs mr-2"></i>FAQ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center"><i class="fas fa-book text-xs mr-2"></i>Connection Guide</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center"><i class="fas fa-bug text-xs mr-2"></i>Bug Reports</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center"><i class="fas fa-envelope text-xs mr-2"></i>Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center"><i class="fas fa-gavel text-xs mr-2"></i>Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white text-xl font-bold mb-6">Newsletter</h3>
                    <p class="text-gray-400 mb-6">Subscribe to our newsletter for the latest news and updates.</p>
                    <form class="flex">
                        <input type="email" placeholder="Your email" class="px-4 py-3 rounded-l-md w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-r-md transition-colors duration-300">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">© {{ date('Y') }} NexusCMS. All rights reserved.</p>
                <p class="text-gray-400 text-sm mt-4 md:mt-0">World of Warcraft and Blizzard Entertainment are trademarks or registered trademarks of Blizzard Entertainment, Inc. in the U.S. and/or other countries.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu functionality
            const mobileMenuButton = document.querySelector('button[type="button"]');
            const mobileMenu = document.createElement('div');
            mobileMenu.className = 'md:hidden';
            mobileMenu.innerHTML = `
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="{{ route('home') }}" class="@if(request()->routeIs('home')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif block px-3 py-2 rounded-md text-base font-medium">Home</a>
                    <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">News</a>
                    <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">How to Play</a>
                    <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Forums</a>
                    <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Armory</a>
                    <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Donate</a>
                </div>
            `;
            mobileMenu.style.display = 'none';
            document.querySelector('nav').appendChild(mobileMenu);

            mobileMenuButton.addEventListener('click', () => {
                const isVisible = mobileMenu.style.display === 'block';
                mobileMenu.style.display = isVisible ? 'none' : 'block';
            });

            // Smooth scroll behavior
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
    @yield('scripts')
</body>
</html>