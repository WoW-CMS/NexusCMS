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
                @foreach(menu_items('ucp') as $item)
                    @php
                        $isActive = !empty($item['route']) ? request()->routeIs($item['route']) : false;
                        $iconClass = $item['icon'] ?? 'fas fa-link w-5';
                    @endphp
                    @if(!empty($item['children']))
                        <div class="pt-2 pb-2 border-t border-slate-700/50 first:border-t-0 first:pt-0">
                            <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ $item['label'] }}</p>
                            <div class="space-y-1">
                                @foreach($item['children'] as $child)
                                    @php $childActive = !empty($child['route']) ? request()->routeIs($child['route']) : false; @endphp
                                    <a href="{{ menu_item_href($child) }}"
                                       class="flex items-center space-x-3 px-4 py-2 rounded-lg transition-all duration-200 text-sm {{ $childActive ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30' : 'text-gray-400 hover:bg-slate-700/50 hover:text-white' }}">
                                        <i class="{{ $child['icon'] ?? 'fas fa-link w-4' }}"></i>
                                        <span class="font-medium">{{ $child['label'] ?? 'Menu' }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ menu_item_href($item) }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30 hover:bg-blue-500/30' : 'text-gray-400 hover:bg-slate-700/50 hover:text-white' }}">
                            <i class="{{ $iconClass }}"></i>
                            <span class="font-medium">{{ $item['label'] ?? 'Menu' }}</span>
                       </a>
                    @endif
                @endforeach
            </div>

            @can('access.gm.panel')
            <div class="mt-6 pt-6 border-t border-slate-700/50">
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">GAMEMASTER</p>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                    <i class="fas fa-shield w-5"></i>
                    <span class="font-medium">GM Panel</span>
                </a>
            </div>
            @endcan

            @can('access.admin.panel')
            <div class="mt-6 pt-6 border-t border-slate-700/50">
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">ADMIN</p>
                <a href="{{ route('admin.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                    <i class="fas fa-cog w-5"></i>
                    <span class="font-medium">Admin Panel</span>
                </a>
            </div>
            @endcan
        </nav>
    </div>
</aside>