<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'NexusCMS') }} - @yield('title', 'AdminCP - Dashboard')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
    <style>
        /* Custom scrollbar for webkit browsers */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #1f2937; /* gray-800 */
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #4b5563; /* gray-600 */
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #6b7280; /* gray-500 */
        }
        /* Firefox */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #4b5563 #1f2937;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0 hidden md:flex flex-col">
            <!-- Logo -->
            <div class="h-16 flex items-center px-6 bg-gray-800 border-b border-gray-700">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center font-bold">
                        N
                    </div>
                    <span class="text-lg font-bold">AdminCP</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 custom-scrollbar">
                <div class="px-3 mb-4">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Main</h3>
                    @can('access.admin.panel')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 bg-blue-600 rounded-lg mb-1">
                        <i class="fas fa-home w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    @endcan
                    @can('access.admin.panel')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-chart-line w-5"></i>
                        <span>Analytics</span>
                    </a>
                    @endcan
                </div>

                <div class="px-3 mb-4">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Management</h3>
                    @can('manage.admin.users')
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-users w-5"></i>
                        <span>Users</span>
                    </a>
                    @endcan
                    @can('manage.characters')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-user-shield w-5"></i>
                        <span>Characters</span>
                    </a>
                    @endcan
                    @can('manage.bans')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-ban w-5"></i>
                        <span>Bans</span>
                    </a>
                    @endcan
                    @can('manage.reports')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-exclamation-triangle w-5"></i>
                        <span>Reports</span>
                        <span class="ml-auto bg-red-500 text-xs px-2 py-0.5 rounded-full">12</span>
                    </a>
                    @endcan
                </div>

                <div class="px-3 mb-4">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Content</h3>
                    @can('manage.news')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-newspaper w-5"></i>
                        <span>News</span>
                    </a>
                    @endcan
                    @can('manage.pages')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-file-alt w-5"></i>
                        <span>Pages</span>
                    </a>
                    @endcan
                    @can('manage.announcements')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-bullhorn w-5"></i>
                        <span>Announcements</span>
                    </a>
                    @endcan
                </div>

                <div class="px-3 mb-4">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Store</h3>
                    @can('manage.store.products')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-shopping-cart w-5"></i>
                        <span>Products</span>
                    </a>
                    @endcan
                    @can('manage.store.categories')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-tags w-5"></i>
                        <span>Categories</span>
                    </a>
                    @endcan
                    @can('view.store.transactions')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-receipt w-5"></i>
                        <span>Transactions</span>
                    </a>
                    @endcan
                </div>

                <div class="px-3 mb-4">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Realms</h3>
                    @can('manage.realms')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-server w-5"></i>
                        <span>Realm Management</span>
                    </a>
                    @endcan
                    @can('manage.realms')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-database w-5"></i>
                        <span>Database</span>
                    </a>
                    @endcan
                    @can('manage.realms')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-envelope w-5"></i>
                        <span>In-Game Mail</span>
                    </a>
                    @endcan
                </div>

                <div class="px-3 mb-4">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">System</h3>
                    @can('manage.roles')
                    <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-user-shield w-5"></i>
                        <span>Roles & Permissions</span>
                    </a>
                    @endcan
                    @can('manage.settings')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-cog w-5"></i>
                        <span>Settings</span>
                    </a>
                    @endcan
                    @can('view.logs')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-terminal w-5"></i>
                        <span>Logs</span>
                    </a>
                    @endcan
                    @can('manage.backups')
                    <a href="#" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-800 rounded-lg mb-1 text-gray-300 hover:text-white transition">
                        <i class="fas fa-download w-5"></i>
                        <span>Backups</span>
                    </a>
                    @endcan
                </div>
            </nav>

            <!-- User Info -->
            <div class="p-4 bg-gray-800 border-t border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center font-bold">
                        A
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold truncate">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-400">{{ Auth::user()->roles->pluck('name')->join(', ') }}</div>
                    </div>
                    <a href="" class="text-gray-400 hover:text-white">
                        <i class="fas fa-cog"></i>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-white">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        <div class="flex-1 flex flex-col overflow-hidden">
            @yield('content')
        </div>
    </div>
    @stack('scripts')
</body>
</html>
