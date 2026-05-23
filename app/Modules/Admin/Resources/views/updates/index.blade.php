@extends('admin::layouts.app')

@section('Title', 'System Updates')

@section('content')
<div class="flex-1 flex flex-col overflow-hidden">

    {{-- Header --}}
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0">
        <div class="flex items-center gap-3">
            <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-cloud-arrow-up text-blue-600"></i>
                System Updates
            </h1>
            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">
                Current: v{{ $current }}
            </span>
        </div>
        <button onclick="checkForUpdates()" id="check-btn"
            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium flex items-center gap-2 transition">
            <i class="fas fa-sync-alt" id="check-icon"></i>
            Check for Updates
        </button>
    </header>

    {{-- Main Content --}}
    <main class="flex-1 overflow-y-auto bg-gray-50 p-6 space-y-6">

        @if(!settings('update.enabled', config('update.enabled', true)))
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 flex items-start gap-4">
            <i class="fas fa-ban text-yellow-500 text-xl mt-0.5"></i>
            <div>
                <p class="font-semibold text-yellow-800">Auto-updates disabled</p>
                <p class="text-sm text-yellow-700 mt-1">Set <code>UPDATE_ENABLED=true</code> in your <code>.env</code> to enable the updater.</p>
            </div>
        </div>
        @elseif(!$latest)
        <div class="bg-white rounded-xl shadow border border-gray-200 p-6 flex items-start gap-4">
            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="fas fa-plug text-gray-400 text-xl"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-800">Could not reach GitHub</p>
                <p class="text-sm text-gray-500 mt-1">
                    Unable to fetch release information. Check your internet connection, verify the repository
                    (<code>{{ settings('update.repo_owner', config('update.repository.owner')) }}/{{ settings('update.repo_name', config('update.repository.name')) }}</code>),
                    or add a <code>GITHUB_TOKEN</code> if the repository is private.
                </p>
            </div>
        </div>
        @elseif($outdated)
        {{-- Update Available --}}
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-blue-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="font-bold text-gray-800">Update Available</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span>{{ $latest['tag_name'] }}</span>
                    @if($latest['published_at'])
                    <span class="text-gray-300">•</span>
                    <span>{{ \Carbon\Carbon::parse($latest['published_at'])->format('M d, Y') }}</span>
                    @endif
                    @if($latest['html_url'])
                    <span class="text-gray-300">•</span>
                    <a href="{{ $latest['html_url'] }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                        <i class="fab fa-github text-xs"></i> View on GitHub
                    </a>
                    @endif
                </div>
            </div>
            <div class="p-6 grid md:grid-cols-3 gap-6">
                <div class="flex items-center gap-4">
                    <div class="text-center">
                        <p class="text-xs text-gray-500 mb-1">Current</p>
                        <span class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-lg font-mono text-sm font-semibold">v{{ $current }}</span>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                    <div class="text-center">
                        <p class="text-xs text-gray-500 mb-1">Latest</p>
                        <span class="px-3 py-1.5 bg-blue-600 text-white rounded-lg font-mono text-sm font-semibold">{{ $latest['tag_name'] }}</span>
                    </div>
                </div>
                <div class="md:col-span-2">
                    @if($latest['body'])
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">Release Notes</p>
                    <div class="text-sm text-gray-700 bg-white rounded-lg border border-gray-200 p-3 max-h-36 overflow-y-auto leading-relaxed whitespace-pre-line">{{ $latest['body'] }}</div>
                    @else
                    <p class="text-sm text-gray-500 italic">No release notes provided.</p>
                    @endif
                </div>
            </div>
            <div class="px-6 pb-6 flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <i class="fas fa-{{ $method === 'git' ? 'code-branch' : 'file-zipper' }} {{ $method === 'git' ? 'text-green-500' : 'text-orange-500' }}"></i>
                    Update method: <strong>{{ strtoupper($method) }}</strong>
                    @php $allOk = collect($checks)->every(fn($c) => $c['ok']) @endphp
                    <span class="ml-3 flex items-center gap-1 {{ $allOk ? 'text-green-600' : 'text-red-600' }}">
                        <i class="fas fa-{{ $allOk ? 'circle-check' : 'circle-exclamation' }} text-xs"></i>
                        {{ $allOk ? 'Pre-flight OK' : 'Pre-flight issues detected' }}
                    </span>
                </div>
                <button onclick="openUpdateModal('{{ $latest['tag_name'] }}')"
                    {{ !$allOk ? 'disabled' : '' }}
                    title="{{ !$allOk ? 'Fix pre-flight issues before updating' : '' }}"
                    class="px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 text-sm flex items-center gap-2 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-md">
                    <i class="fas fa-cloud-arrow-up"></i>
                    Apply Update {{ $latest['tag_name'] }}
                </button>
            </div>
        </div>
        @else
        {{-- Up to Date --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow p-6 flex items-center gap-5">
            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="fas fa-circle-check text-green-500 text-2xl"></i>
            </div>
            <div class="flex-1">
                <p class="font-bold text-gray-800 text-lg">You're running the latest version</p>
                <p class="text-sm text-gray-500 mt-0.5">
                    NexusCMS <strong>v{{ $current }}</strong>
                    @if(($currentRelease['published_at'] ?? null))
                    &mdash; released {{ \Carbon\Carbon::parse($currentRelease['published_at'])->diffForHumans() }}
                    @elseif($latest['published_at'] ?? null)
                    &mdash; released {{ \Carbon\Carbon::parse($latest['published_at'])->diffForHumans() }}
                    @endif
                </p>
            </div>
            @if(($currentRelease['html_url'] ?? null) ?? ($latest['html_url'] ?? null))
            <a href="{{ $currentRelease['html_url'] ?? $latest['html_url'] }}" target="_blank"
               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 flex items-center gap-2 transition">
                <i class="fab fa-github"></i> Release Notes
            </a>
            @endif
        </div>
        @endif

        {{-- Pre-flight Checks --}}
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <button onclick="toggleSection('preflight')"
                class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-gray-50 transition">
                <span class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-list-check text-indigo-500"></i>
                    Pre-flight Checks
                    @php $allOk = collect($checks)->every(fn($c) => $c['ok']) @endphp
                    <span class="text-xs font-normal px-2 py-0.5 rounded-full {{ $allOk ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ collect($checks)->where('ok', true)->count() }}/{{ count($checks) }} passed
                    </span>
                </span>
                <i class="fas fa-chevron-down text-gray-400 transition-transform" id="preflight-chevron"></i>
            </button>
            <div id="preflight" class="hidden border-t border-gray-100">
                <div class="p-6 grid sm:grid-cols-2 gap-3">
                    @foreach($checks as $check)
                    <div class="flex items-start gap-3 p-3 rounded-lg {{ $check['ok'] ? 'bg-green-50 border border-green-100' : 'bg-red-50 border border-red-100' }}">
                        <i class="fas fa-{{ $check['ok'] ? 'circle-check text-green-500' : 'circle-xmark text-red-500' }} mt-0.5 shrink-0"></i>
                        <div class="min-w-0">
                            <p class="text-sm font-medium {{ $check['ok'] ? 'text-green-800' : 'text-red-800' }}">{{ $check['name'] }}</p>
                            @if($check['detail'] ?? '')
                            <p class="text-xs {{ $check['ok'] ? 'text-green-600' : 'text-red-600' }} mt-0.5 truncate" title="{{ $check['detail'] }}">{{ $check['detail'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Recent Releases --}}
        @if(!empty($releases))
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-tags text-gray-400"></i>
                <h3 class="font-semibold text-gray-800">Recent Releases</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($releases as $release)
                <div class="px-6 py-4 flex items-center gap-4">
                    <span class="font-mono text-sm font-semibold text-gray-800 w-24 shrink-0">{{ $release['tag_name'] }}</span>
                    <span class="text-sm text-gray-600 flex-1 truncate">{{ $release['name'] ?? '' }}</span>
                    @if($release['prerelease'] ?? false)
                    <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">pre-release</span>
                    @endif
                    @if($release['published_at'] ?? null)
                    <span class="text-xs text-gray-400 shrink-0">{{ \Carbon\Carbon::parse($release['published_at'])->format('Y-m-d') }}</span>
                    @endif
                    @if($release['html_url'] ?? null)
                    <a href="{{ $release['html_url'] }}" target="_blank"
                       class="text-xs text-blue-600 hover:underline shrink-0 flex items-center gap-1">
                        <i class="fas fa-arrow-up-right-from-square text-[10px]"></i> Notes
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Update History --}}
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-clock-rotate-left text-gray-400"></i>
                <h3 class="font-semibold text-gray-800">Update History</h3>
            </div>
            @if($history->isEmpty())
            <div class="px-6 py-8 text-center text-gray-400">
                <i class="fas fa-inbox text-3xl mb-2 block"></i>
                <p class="text-sm">No updates applied yet.</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">From</th>
                            <th class="px-6 py-3 text-left">To</th>
                            <th class="px-6 py-3 text-left">Method</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">By</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Log</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($history as $entry)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-mono text-gray-600">{{ $entry->from_version ?? '—' }}</td>
                            <td class="px-6 py-3 font-mono font-semibold text-gray-800">{{ $entry->to_version }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-0.5 text-xs rounded-full {{ $entry->method === 'git' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                    {{ $entry->method }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full font-medium
                                    {{ $entry->status === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    <i class="fas fa-{{ $entry->status === 'success' ? 'check' : 'xmark' }} text-[10px]"></i>
                                    {{ $entry->status }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-600">{{ $entry->executor?->name ?? 'System' }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $entry->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-3">
                                @if($entry->notes)
                                <button onclick="showLog({{ $entry->id }})"
                                    class="text-blue-600 hover:underline text-xs flex items-center gap-1">
                                    <i class="fas fa-terminal text-[10px]"></i> View
                                </button>
                                <pre id="log-{{ $entry->id }}" class="hidden mt-2 bg-gray-900 text-green-400 text-xs rounded p-3 overflow-x-auto max-w-xs max-h-48 leading-relaxed">{{ $entry->notes }}</pre>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    </main>
</div>

{{-- Update Confirmation Modal --}}
<div id="update-modal" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-cloud-arrow-up text-blue-600"></i>
                Apply Update <span id="modal-tag" class="text-blue-600 ml-1"></span>
            </h2>
            <button onclick="closeUpdateModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-xmark text-lg"></i>
            </button>
        </div>
        <div id="modal-confirm" class="p-6 space-y-4">
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 space-y-2">
                <p class="text-sm font-semibold text-amber-800 flex items-center gap-2">
                    <i class="fas fa-triangle-exclamation"></i> Before you proceed
                </p>
                <ul class="text-sm text-amber-700 space-y-1 list-disc list-inside">
                    <li>Back up your <strong>database</strong> before updating</li>
                    <li>The site will enter <strong>maintenance mode</strong> briefly</li>
                    <li>Active sessions will be interrupted</li>
                    <li>This action <strong>cannot be undone</strong> automatically</li>
                </ul>
            </div>
            <p class="text-sm text-gray-600">
                Update method: <strong class="capitalize">{{ $method }}</strong>.
                Migrations will run automatically after files are applied.
            </p>
        </div>
        <div id="modal-progress" class="hidden p-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin shrink-0"></div>
                <p class="text-sm font-medium text-gray-700">Applying update — please do not close this page...</p>
            </div>
        </div>
        <div id="modal-result" class="hidden p-6 space-y-3">
            <div id="result-banner" class="rounded-lg p-4 text-sm font-semibold"></div>
            <pre id="result-log" class="bg-gray-900 text-green-400 text-xs rounded-lg p-4 overflow-y-auto max-h-64 leading-relaxed"></pre>
        </div>
        <div id="modal-footer" class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
            <button onclick="closeUpdateModal()"
                class="px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Cancel
            </button>
            <button id="confirm-btn" onclick="applyUpdate()"
                class="px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition flex items-center gap-2 shadow">
                <i class="fas fa-cloud-arrow-up"></i> Apply Update
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let pendingTag = null;

function openUpdateModal(tag) {
    pendingTag = tag;
    document.getElementById('modal-tag').textContent = tag;
    document.getElementById('modal-confirm').classList.remove('hidden');
    document.getElementById('modal-progress').classList.add('hidden');
    document.getElementById('modal-result').classList.add('hidden');
    document.getElementById('modal-footer').classList.remove('hidden');
    document.getElementById('confirm-btn').disabled = false;
    document.getElementById('confirm-btn').classList.remove('hidden');
    document.getElementById('update-modal').classList.remove('hidden');
    document.getElementById('update-modal').classList.add('flex');
}

function closeUpdateModal() {
    document.getElementById('update-modal').classList.add('hidden');
    document.getElementById('update-modal').classList.remove('flex');
    pendingTag = null;
}

async function applyUpdate() {
    if (!pendingTag) return;
    document.getElementById('modal-confirm').classList.add('hidden');
    document.getElementById('modal-progress').classList.remove('hidden');
    document.getElementById('modal-footer').classList.add('hidden');

    try {
        const response = await fetch('{{ route("admin.updates.apply") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ tag: pendingTag }),
        });

        const data = await response.json();
        document.getElementById('modal-progress').classList.add('hidden');
        document.getElementById('modal-result').classList.remove('hidden');

        const banner = document.getElementById('result-banner');
        if (data.success) {
            banner.className = 'rounded-lg p-4 text-sm font-semibold bg-green-100 text-green-800';
            banner.innerHTML = '<i class="fas fa-circle-check mr-2"></i>Update applied successfully! Reloading in 5 seconds...';
            setTimeout(() => window.location.reload(), 5000);
        } else {
            banner.className = 'rounded-lg p-4 text-sm font-semibold bg-red-100 text-red-800';
            banner.innerHTML = '<i class="fas fa-circle-xmark mr-2"></i>Update failed: ' + (data.error ?? 'Unknown error');
        }

        document.getElementById('result-log').textContent = (data.log ?? []).join('\n');
        document.getElementById('modal-footer').classList.remove('hidden');
        document.getElementById('confirm-btn').classList.add('hidden');

    } catch (err) {
        document.getElementById('modal-progress').classList.add('hidden');
        document.getElementById('modal-result').classList.remove('hidden');
        const banner = document.getElementById('result-banner');
        banner.className = 'rounded-lg p-4 text-sm font-semibold bg-red-100 text-red-800';
        banner.innerHTML = '<i class="fas fa-circle-xmark mr-2"></i>Network error: ' + err.message;
        document.getElementById('modal-footer').classList.remove('hidden');
        document.getElementById('confirm-btn').classList.add('hidden');
    }
}

function checkForUpdates() {
    document.getElementById('check-btn').disabled = true;
    document.getElementById('check-icon').classList.add('fa-spin');
    setTimeout(() => window.location.reload(), 400);
}

function toggleSection(id) {
    document.getElementById(id).classList.toggle('hidden');
    document.getElementById(id + '-chevron').classList.toggle('rotate-180');
}

function showLog(id) {
    document.getElementById('log-' + id).classList.toggle('hidden');
}

document.getElementById('update-modal').addEventListener('click', function(e) {
    if (e.target === this) closeUpdateModal();
});
</script>
@endpush
