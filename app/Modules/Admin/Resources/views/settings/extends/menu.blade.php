<div class="flex">
    <!-- Settings Sidebar -->
    <div class="w-64 bg-white border-r border-gray-200 p-4">
        <nav class="space-y-1">
            @php
                $currentView = request('view', 'general');
                $menuItems = [
                    'general' => ['icon' => 'globe', 'label' => 'General'],
                    'email' => ['icon' => 'envelope', 'label' => 'Email'],
                    'database' => ['icon' => 'database', 'label' => 'Database'],
                    'realms' => ['icon' => 'server', 'label' => 'Realms'],
                    'payment' => ['icon' => 'credit-card', 'label' => 'Payment'],
                    'security' => ['icon' => 'shield-alt', 'label' => 'Security'],
                    'appearance' => ['icon' => 'palette', 'label' => 'Appearance'],
                    'seo' => ['icon' => 'search', 'label' => 'SEO'],
                    'api' => ['icon' => 'code', 'label' => 'API'],
                    'maintenance' => ['icon' => 'tools', 'label' => 'Maintenance'],
                    'localization' => ['icon' => 'language', 'label' => 'Localization'],
                    'advanced' => ['icon' => 'cogs', 'label' => 'Advanced'],
                ];
            @endphp

            @foreach($menuItems as $key => $item)
                <a href="{{ route('admin.settings.index', ['view' => $key]) }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg {{ $currentView === $key ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-{{ $item['icon'] }} w-5"></i>{{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </div>
</div>