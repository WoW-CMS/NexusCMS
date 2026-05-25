@extends('admin::settings.index')

@section('settings_content')
<div class="space-y-6">

    {{-- Repository --}}
    <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                <i class="fab fa-github text-gray-500"></i>
                Source Repository
            </h2>
            <p class="text-sm text-gray-500 mt-0.5">GitHub repository to fetch updates from.</p>
        </div>
        <div class="p-6 grid sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Repository Owner</label>
                <input type="text" name="update_repo_owner"
                    value="{{ old('update_repo_owner', $settings->get('update_repo_owner', 'wow-cms')) }}"
                    placeholder="e.g. wow-cms"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-xs text-gray-400 mt-1">GitHub username or organisation.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Repository Name</label>
                <input type="text" name="update_repo_name"
                    value="{{ old('update_repo_name', $settings->get('update_repo_name', 'nexuscms')) }}"
                    placeholder="e.g. nexuscms"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-xs text-gray-400 mt-1">Exact repository name (case-sensitive).</p>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    GitHub Token
                    <span class="text-xs font-normal text-gray-400 ml-1">(optional)</span>
                </label>
                <input type="password" name="update_github_token"
                    value="{{ old('update_github_token', $settings->get('update_github_token', '')) }}"
                    placeholder="ghp_xxxxxxxxxxxx"
                    autocomplete="new-password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono">
                <p class="text-xs text-gray-400 mt-1">
                    Required for private repositories. Also increases the GitHub API rate limit from 60 to 5,000 requests/hour.
                    Leave empty to use unauthenticated requests.
                </p>
            </div>
        </div>
    </div>

    {{-- Update Behaviour --}}
    <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-sliders text-gray-500"></i>
                Update Behaviour
            </h2>
        </div>
        <div class="p-6 space-y-6">

            {{-- Channel --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Release Channel</label>
                <div class="grid sm:grid-cols-3 gap-3">
                    @php
                        $currentChannel = $settings->get('update_channel', 'any');
                        $channels = [
                            'stable' => ['label' => 'Stable', 'desc' => 'Only full releases', 'icon' => 'fa-shield-check', 'color' => 'green'],
                            'beta'   => ['label' => 'Beta',   'desc' => 'Includes pre-releases', 'icon' => 'fa-flask', 'color' => 'orange'],
                            'any'    => ['label' => 'Any',    'desc' => 'All published releases', 'icon' => 'fa-bolt', 'color' => 'red'],
                        ];
                    @endphp
                    @foreach($channels as $val => $ch)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="update_channel" value="{{ $val }}"
                            {{ $currentChannel === $val ? 'checked' : '' }}
                            class="sr-only peer">
                        <div class="border-2 rounded-xl p-4 transition
                            peer-checked:border-blue-500 peer-checked:bg-blue-50
                            border-gray-200 hover:border-gray-300">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas {{ $ch['icon'] }} text-{{ $ch['color'] }}-500 text-sm"></i>
                                <span class="text-sm font-semibold text-gray-800">{{ $ch['label'] }}</span>
                                <i class="fas fa-circle-check text-blue-500 ml-auto hidden peer-checked:block"></i>
                            </div>
                            <p class="text-xs text-gray-500">{{ $ch['desc'] }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Toggles --}}
            <div class="grid sm:grid-cols-2 gap-6">
                {{-- Enabled --}}
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Auto-update System</p>
                        <p class="text-xs text-gray-400 mt-0.5">Enable or disable the update checker and apply button.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="hidden" name="update_enabled" value="0">
                        <input type="checkbox" name="update_enabled" value="1"
                            {{ $settings->get('update_enabled', '1') == '1' ? 'checked' : '' }}
                            class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer
                                    peer-checked:after:translate-x-full peer-checked:after:border-white
                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                    after:bg-white after:border-gray-300 after:border after:rounded-full
                                    after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                {{-- Maintenance mode --}}
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Maintenance Mode During Update</p>
                        <p class="text-xs text-gray-400 mt-0.5">Put the site in maintenance mode while applying an update.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="hidden" name="update_maintenance_mode" value="0">
                        <input type="checkbox" name="update_maintenance_mode" value="1"
                            {{ $settings->get('update_maintenance_mode', '1') == '1' ? 'checked' : '' }}
                            class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer
                                    peer-checked:after:translate-x-full peer-checked:after:border-white
                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                    after:bg-white after:border-gray-300 after:border after:rounded-full
                                    after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- Info box --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3 text-sm text-blue-800">
        <i class="fas fa-circle-info text-blue-500 mt-0.5 shrink-0"></i>
        <p>
            These settings are stored in the database and take effect immediately without redeploying.
            To apply updates, go to <a href="{{ route('admin.updates.index') }}" class="font-semibold underline">System &rsaquo; Updates</a>.
        </p>
    </div>

</div>
@endsection
