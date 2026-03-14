<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'NexusCMS') }} · @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Sidebar scrollbar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: #4b5563; }
        .sidebar-scroll { scrollbar-width: thin; scrollbar-color: #374151 transparent; }

        /* Nav item active indicator */
        .nav-item-active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: #3b82f6;
            border-radius: 0 3px 3px 0;
        }

        /* Mobile overlay */
        #sidebar-overlay { transition: opacity 0.2s ease; }

        /* Sidebar slide */
        #sidebar { transition: transform 0.25s cubic-bezier(0.4,0,0.2,1); }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- ─── Mobile overlay ─────────────────────────────────────────────── --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 bg-black/50 z-20 hidden md:hidden"
         onclick="closeSidebar()"></div>

    {{-- ─── Sidebar ─────────────────────────────────────────────────────── --}}
    <aside id="sidebar"
           class="fixed md:relative z-30 w-64 h-full bg-[#0f1117] text-white flex flex-col flex-shrink-0
                  -translate-x-full md:translate-x-0">

        {{-- Logo / Brand --}}
        <div class="flex items-center gap-3 px-5 h-16 border-b border-white/[0.06] shrink-0">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div class="leading-tight">
                <span class="text-sm font-bold tracking-tight text-white">{{ config('app.name', 'NexusCMS') }}</span>
                <span class="block text-[10px] font-medium text-blue-400 uppercase tracking-widest">Admin Panel</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto sidebar-scroll py-4 px-3 space-y-6">

            {{-- ── Main ── --}}
            <div>
                <p class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-widest text-gray-500 select-none">Main</p>
                @can('access.admin.panel')
                @php $active = request()->routeIs('admin.index'); @endphp
                <a href="{{ route('admin.index') }}"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          {{ $active ? 'nav-item-active bg-white/[0.08] text-white' : 'text-gray-400 hover:bg-white/[0.05] hover:text-gray-200' }}">
                    <i class="fas fa-house-chimney w-4 text-center {{ $active ? 'text-blue-400' : '' }}"></i>
                    Dashboard
                </a>
                @endcan
                @can('access.admin.panel')
                @php $active = request()->routeIs('admin.analytics.index'); @endphp
                <a href="{{ route('admin.analytics.index') }}"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          {{ $active ? 'nav-item-active bg-white/[0.08] text-white' : 'text-gray-400 hover:bg-white/[0.05] hover:text-gray-200' }}">
                    <i class="fas fa-chart-line w-4 text-center {{ $active ? 'text-blue-400' : '' }}"></i>
                    Analytics
                </a>
                @endcan
            </div>

            {{-- ── Management ── --}}
            <div>
                <p class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-widest text-gray-500 select-none">Management</p>
                @can('manage.admin.users')
                @php $active = request()->routeIs('admin.users.*'); @endphp
                <a href="{{ route('admin.users.index') }}"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          {{ $active ? 'nav-item-active bg-white/[0.08] text-white' : 'text-gray-400 hover:bg-white/[0.05] hover:text-gray-200' }}">
                    <i class="fas fa-users w-4 text-center {{ $active ? 'text-blue-400' : '' }}"></i>
                    Users
                </a>
                @endcan
                @can('manage.characters')
                <a href="#"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          text-gray-400 hover:bg-white/[0.05] hover:text-gray-200">
                    <i class="fas fa-shield-halved w-4 text-center"></i>
                    Characters
                </a>
                @endcan
                @can('manage.bans')
                <a href="#"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          text-gray-400 hover:bg-white/[0.05] hover:text-gray-200">
                    <i class="fas fa-ban w-4 text-center"></i>
                    Bans
                </a>
                @endcan
                @can('manage.reports')
                <a href="#"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          text-gray-400 hover:bg-white/[0.05] hover:text-gray-200">
                    <i class="fas fa-flag w-4 text-center"></i>
                    Reports
                    <span class="ml-auto inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold bg-red-500 text-white rounded-full">12</span>
                </a>
                @endcan
            </div>

            {{-- ── Content ── --}}
            <div>
                <p class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-widest text-gray-500 select-none">Content</p>
                @can('manage.news')
                <a href="#"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          text-gray-400 hover:bg-white/[0.05] hover:text-gray-200">
                    <i class="fas fa-newspaper w-4 text-center"></i>
                    News
                </a>
                @endcan
                @can('manage.pages')
                <a href="#"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          text-gray-400 hover:bg-white/[0.05] hover:text-gray-200">
                    <i class="fas fa-file-lines w-4 text-center"></i>
                    Pages
                </a>
                @endcan
                @can('manage.announcements')
                <a href="#"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          text-gray-400 hover:bg-white/[0.05] hover:text-gray-200">
                    <i class="fas fa-bullhorn w-4 text-center"></i>
                    Announcements
                </a>
                @endcan
            </div>

            {{-- ── Store ── --}}
            <div>
                <p class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-widest text-gray-500 select-none">Store</p>
                @can('manage.store.products')
                <a href="#"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          text-gray-400 hover:bg-white/[0.05] hover:text-gray-200">
                    <i class="fas fa-bag-shopping w-4 text-center"></i>
                    Products
                </a>
                @endcan
                @can('manage.store.categories')
                <a href="#"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          text-gray-400 hover:bg-white/[0.05] hover:text-gray-200">
                    <i class="fas fa-tags w-4 text-center"></i>
                    Categories
                </a>
                @endcan
                @can('view.store.transactions')
                <a href="#"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          text-gray-400 hover:bg-white/[0.05] hover:text-gray-200">
                    <i class="fas fa-receipt w-4 text-center"></i>
                    Transactions
                </a>
                @endcan
            </div>

            {{-- ── Realms ── --}}
            <div>
                <p class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-widest text-gray-500 select-none">Realms</p>
                @can('manage.realms')
                <a href="#"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          text-gray-400 hover:bg-white/[0.05] hover:text-gray-200">
                    <i class="fas fa-server w-4 text-center"></i>
                    Realm Management
                </a>
                <a href="#"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          text-gray-400 hover:bg-white/[0.05] hover:text-gray-200">
                    <i class="fas fa-database w-4 text-center"></i>
                    Database
                </a>
                <a href="#"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          text-gray-400 hover:bg-white/[0.05] hover:text-gray-200">
                    <i class="fas fa-envelope w-4 text-center"></i>
                    In-Game Mail
                </a>
                @endcan
            </div>

            {{-- ── System ── --}}
            <div>
                <p class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-widest text-gray-500 select-none">System</p>
                @can('manage.roles')
                @php $active = request()->routeIs('admin.roles.*'); @endphp
                <a href="{{ route('admin.roles.index') }}"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          {{ $active ? 'nav-item-active bg-white/[0.08] text-white' : 'text-gray-400 hover:bg-white/[0.05] hover:text-gray-200' }}">
                    <i class="fas fa-user-lock w-4 text-center {{ $active ? 'text-blue-400' : '' }}"></i>
                    Roles & Permissions
                </a>
                @endcan
                @can('manage.settings')
                @php $active = request()->routeIs('admin.settings.*'); @endphp
                <a href="{{ route('admin.settings.index') }}"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          {{ $active ? 'nav-item-active bg-white/[0.08] text-white' : 'text-gray-400 hover:bg-white/[0.05] hover:text-gray-200' }}">
                    <i class="fas fa-sliders w-4 text-center {{ $active ? 'text-blue-400' : '' }}"></i>
                    Settings
                </a>
                @endcan
                @can('view.logs')
                @php $active = request()->routeIs('admin.logs.*'); @endphp
                <a href="{{ route('admin.logs.index') }}"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          {{ $active ? 'nav-item-active bg-white/[0.08] text-white' : 'text-gray-400 hover:bg-white/[0.05] hover:text-gray-200' }}">
                    <i class="fas fa-terminal w-4 text-center {{ $active ? 'text-blue-400' : '' }}"></i>
                    Logs
                </a>
                @endcan
                @can('manage.backups')
                @php $active = request()->routeIs('admin.backups.*'); @endphp
                <a href="{{ route('admin.backups.index') }}"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          {{ $active ? 'nav-item-active bg-white/[0.08] text-white' : 'text-gray-400 hover:bg-white/[0.05] hover:text-gray-200' }}">
                    <i class="fas fa-cloud-arrow-down w-4 text-center {{ $active ? 'text-blue-400' : '' }}"></i>
                    Backups
                </a>
                @endcan
                @can('manage.modules')
                @php $active = request()->routeIs('admin.modules.*'); @endphp
                <a href="{{ route('admin.modules.index') }}"
                   class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 mb-0.5
                          {{ $active ? 'nav-item-active bg-white/[0.08] text-white' : 'text-gray-400 hover:bg-white/[0.05] hover:text-gray-200' }}">
                    <i class="fas fa-puzzle-piece w-4 text-center {{ $active ? 'text-blue-400' : '' }}"></i>
                    Modules
                </a>
                @endcan
            </div>

        </nav>

        {{-- User footer --}}
        <div class="shrink-0 border-t border-white/[0.06] px-4 py-3">
            <div class="flex items-center gap-3">
                {{-- Avatar with initials --}}
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-xs font-bold shrink-0 uppercase select-none">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate leading-tight">{{ Auth::user()->name }}</p>
                    <p class="text-[11px] text-gray-500 truncate leading-tight">{{ Auth::user()->roles->pluck('name')->join(', ') }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit"
                            title="Sign out"
                            class="w-7 h-7 flex items-center justify-center rounded-md text-gray-500 hover:text-red-400 hover:bg-red-400/10 transition">
                        <i class="fas fa-arrow-right-from-bracket text-xs"></i>
                    </button>
                </form>
            </div>
        </div>

    </aside>

    {{-- ─── Main area ───────────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Top bar (mobile) --}}
        <header class="md:hidden h-14 bg-white border-b border-gray-200 flex items-center justify-between px-4 shrink-0 z-10">
            <button onclick="openSidebar()"
                    class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 transition">
                <i class="fas fa-bars"></i>
            </button>
            <span class="text-sm font-semibold text-gray-800">@yield('title', 'Dashboard')</span>
            <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-xs font-bold text-white uppercase">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto">
            @yield('content')
        </main>

    </div>
</div>

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.remove('hidden');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.add('hidden');
    }
</script>

@stack('scripts')
</body>
</html>
