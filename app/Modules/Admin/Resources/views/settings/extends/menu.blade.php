<div class="flex">
    <!-- Settings Sidebar -->
    <div class="w-64 bg-white border-r border-gray-200 p-4">
        @can('manage.settings')
        <nav class="space-y-1">
            @php
                $currentView = request('view', 'general');
                $menuItems = [
                    'general' => ['icon' => 'globe', 'label' => __('admin::settings.menu.general')],
                    'email' => ['icon' => 'envelope', 'label' => __('admin::settings.menu.email')],
                    'payment' => ['icon' => 'credit-card', 'label' => __('admin::settings.menu.payment')],
                    'security' => ['icon' => 'shield-alt', 'label' => __('admin::settings.menu.security')],  
                    'appearance' => ['icon' => 'palette', 'label' => __('admin::settings.menu.appearance')],
                    'seo' => ['icon' => 'search', 'label' => __('admin::settings.menu.seo')],  
                    'api' => ['icon' => 'code', 'label' => __('admin::settings.menu.api')],
                    'maintenance' => ['icon' => 'tools', 'label' => __('admin::settings.menu.maintenance')],        
                    'localization' => ['icon' => 'language', 'label' => __('admin::settings.menu.localization')],
                    'advanced' => ['icon' => 'cogs', 'label' => __('admin::settings.menu.advanced')],
                    'updates'  => ['icon' => 'cloud-arrow-up', 'label' => 'Updates'],
                ];
            @endphp

            @foreach($menuItems as $key => $item)
                <a href="{{ route('admin.settings.index', ['view' => $key]) }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg {{ $currentView === $key ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-{{ $item['icon'] }} w-5"></i>{{ $item['label'] }}
                </a>
            @endforeach
        </nav>
        @endcan
    </div>
</div>
