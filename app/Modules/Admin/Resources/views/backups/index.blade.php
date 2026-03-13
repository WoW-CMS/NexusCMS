@extends('admin::layouts.app')

@section('title', 'Backups')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Backups</h1>
    </div>
    <div class="flex items-center gap-3">
        <form method="POST" action="{{ route('admin.backups.create') }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium"
                onclick="this.disabled=true; this.innerText='Creating...'; this.form.submit();">
                <i class="fas fa-plus mr-2"></i>Create Backup
            </button>
        </form>
    </div>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex items-center gap-3">
            <i class="fas fa-check-circle text-green-600"></i>
            <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-600"></i>
            <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-archive text-blue-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Backups</p>
                <p class="text-xl font-bold text-gray-800">{{ $backups->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-hdd text-purple-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Storage Used</p>
                <p class="text-xl font-bold text-gray-800">{{ formatBytes($totalSize) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-green-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Latest Backup</p>
                <p class="text-xl font-bold text-gray-800">
                    {{ $latestBackup ? \Carbon\Carbon::createFromTimestamp($latestBackup['date'])->diffForHumans() : 'Never' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Backup List --}}
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">Backup Files</h2>
            <span class="text-sm text-gray-500">{{ $backups->count() }} {{ Str::plural('backup', $backups->count()) }}</span>
        </div>
        <div class="p-6">
            @if($backups->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Filename</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($backups as $backup)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-file-archive text-gray-400"></i>
                                            <span class="text-sm font-medium text-gray-900">{{ $backup['filename'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ formatBytes($backup['size']) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::createFromTimestamp($backup['date'])->format('M d, Y H:i:s') }}
                                        <span class="text-gray-400 ml-1">({{ \Carbon\Carbon::createFromTimestamp($backup['date'])->diffForHumans() }})</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.backups.download', $backup['filename']) }}"
                                                class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 text-sm font-medium">
                                                <i class="fas fa-download mr-1"></i>Download
                                            </a>
                                            <form method="POST" action="{{ route('admin.backups.destroy', $backup['filename']) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this backup? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 text-sm font-medium">
                                                    <i class="fas fa-trash mr-1"></i>Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-archive text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 font-medium">No backups found</p>
                    <p class="text-sm text-gray-400 mt-1">Create your first backup to get started.</p>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
