<aside class="w-full lg:w-72 space-y-6">
    <div class="bg-gray-800/70 backdrop-blur-lg rounded-2xl border border-gray-800 p-8 shadow-2xl">
        <div class="flex items-center space-x-5 mb-8">
            <div class="relative">
                <div class="p-1 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500">
                    <img src="{{ $user->avatar ?? null }}" 
                         alt="Avatar" 
                         class="w-16 h-16 rounded-full ring-2 ring-gray-800">
                </div>
                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-500 border border-gray-800 rounded-full shadow-lg"></div>
            </div>
            <div>
                <h3 class="text-xl font-bold text-white tracking-tight">{{ $user->name }}</h3>
            </div>
        </div>
    
        <nav class="space-y-2">
            <a href="{{ route('ucp.dashboard') }}" 
               class="flex items-center space-x-3 px-5 py-3.5 rounded-xl transition-all duration-300 {{ request()->routeIs('ucp.dashboard') ? 'bg-gradient-to-r from-blue-600/30 to-indigo-600/30 text-blue-400 border border-gray-900 shadow-lg' : 'text-gray-400 hover:bg-gray-700/50 hover:text-white hover:shadow-md' }}">
                <i class="fas fa-home w-5 h-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>
        </nav>
    </div>
</aside>