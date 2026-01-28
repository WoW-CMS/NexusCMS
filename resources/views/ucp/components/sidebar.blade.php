<aside class="w-full lg:w-72 space-y-6">
    <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 overflow-hidden shadow-xl">
        <div class="p-6 bg-gradient-to-br from-blue-500/10 to-purple-500/10 border-b border-slate-700/50">
            <div class="flex flex-col items-center">
                <div class="relative">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 p-0.5">
                        <div class="w-full h-full rounded-full bg-slate-800 flex items-center justify-center text-2xl font-bold">
                            {{ $user->name[0] }}
                        </div>
                    </div>
                    <div class="absolute bottom-0 right-0 w-5 h-5 bg-green-500 rounded-full border-2 border-slate-800"></div>
                </div>
                <h3 class="mt-4 text-lg font-bold text-white">{{ $user->name }}</h3>
                @if($user->roles->isNotEmpty())
                    <span class="mt-1 px-3 py-1 text-xs font-medium bg-purple-500/20 text-purple-300 rounded-full border border-purple-500/30">{{ $user->roles->first()->name }}</span>
                @endif
            </div>
        </div>
        
        <nav class="p-4">
            <div class="space-y-2">
                <a href="{{ route('ucp.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl bg-blue-500/20 text-blue-300 border border-blue-500/30 transition-all duration-200 hover:bg-blue-500/30">
                    <i class="fas fa-home w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="{{ route('ucp.gameaccount') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                    <i class="fas fa-gamepad w-5"></i>
                    <span class="font-medium">Game Account</span>
                </a>
                <a href="{{ route('ucp.transaction') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                    <i class="fas fa-hand-holding-usd w-5"></i>
                    <span class="font-medium">Donations</span>
                </a>
            </div>

            @if($user->hasRole(['GameMaster', 'Admin']))
            <div class="mt-6 pt-6 border-t border-slate-700/50">
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">GAMEMASTER</p>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                    <i class="fas fa-shield w-5"></i>
                    <span class="font-medium">GM Panel</span>
                </a>
            </div>
            @endif

            @if($user->hasRole('Admin'))
            <div class="mt-6 pt-6 border-t border-slate-700/50">
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">ADMIN</p>
                <a href="{{ route('admin.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                    <i class="fas fa-cog w-5"></i>
                    <span class="font-medium">Admin Panel</span>
                </a>
            </div>
            @endif
        </nav>
    </div>
</aside>