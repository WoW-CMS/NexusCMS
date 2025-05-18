@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-900 to-gray-950 pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            @include('ucp.components.sidebar')

            <div class="flex-1 space-y-6">
                <!-- Header Section -->
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-900 p-6 shadow-xl">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">
                            Game Accounts
                        </h2>
                        <button class="px-4 py-2 bg-indigo-600/20 text-indigo-400 rounded-lg border border-indigo-500/20 hover:bg-indigo-600/30 transition-all duration-300">
                            <i class="fas fa-plus mr-2"></i> Add Account
                        </button>
                    </div>

                    <!-- Game Accounts Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse ($gameAccounts ?? [] as $account)
                            <div class="bg-gray-900/50 rounded-lg p-6 border border-gray-800 hover:border-indigo-500/20 transition-all duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-gamepad text-2xl text-indigo-400"></i>
                                        <div>
                                            <h3 class="text-lg font-semibold text-white">{{ $account->username }}</h3>
                                            <p class="text-sm text-gray-400">Created: {{ $account->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button class="p-2 text-gray-400 hover:text-white transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="p-2 text-red-400 hover:text-red-500 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-400">Status</span>
                                        <span class="px-2 py-1 text-xs font-medium text-emerald-400 bg-emerald-400/10 rounded-full">
                                            Active
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-400">Characters</span>
                                        <span class="text-white">{{ count($account->characters ?? []) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-400">Last Login</span>
                                        <span class="text-white">{{ $account->last_login ?? 'Never' }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 bg-gray-900/50 rounded-xl p-12 text-center border border-gray-800">
                                <div class="animate-pulse mb-8">
                                    <i class="fas fa-gamepad text-6xl text-gray-600"></i>
                                </div>
                                <h3 class="text-2xl font-semibold text-gray-400 mb-4">No Game Accounts Found</h3>
                                <p class="text-gray-500">Create your first game account to start playing.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection