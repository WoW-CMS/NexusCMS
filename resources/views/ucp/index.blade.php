@extends('layouts.main')

@section('content')

<div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8 py-8 pt-24">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        @include('ucp.components.sidebar')

        <main class="lg:col-span-3 space-y-6">
            <!-- Account Overview -->
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 overflow-hidden shadow-xl">
                <div class="p-6 border-b border-slate-700/50 flex justify-between items-center">
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">Account Overview</h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Status Card -->
                        <div class="bg-gradient-to-br from-blue-500/10 to-blue-600/5 rounded-xl p-5 border border-blue-500/20 hover:border-blue-500/40 transition-all duration-200 hover:transform hover:scale-105">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center">
                                    <i class="fas fa-shield text-blue-400"></i>
                                </div>
                                <span class="text-sm text-gray-400">Status</span>
                            </div>
                            @switch($user->status ?? 'active')
                                @case('active')
                                    <span class="text-2xl font-bold text-green-400">
                                        Active
                                    </span>
                                    @break
                                @case('inactive')
                                    <span class="text-2xl font-bold text-amber-400">
                                        Inactive
                                    </span>
                                    @break
                                @case('banned')
                                    <span class="text-2xl font-bold text-red-400">
                                        Banned
                                    </span>
                                    @break
                                @default
                                    <span class="text-2xl font-bold text-gray-400">
                                        Unknown Status
                                    </span>
                            @endswitch
                        </div>

                        <!-- Characters Card -->
                        <div class="bg-gradient-to-br from-purple-500/10 to-purple-600/5 rounded-xl p-5 border border-purple-500/20 hover:border-purple-500/40 transition-all duration-200 hover:transform hover:scale-105">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center">
                                    <i class="fas fa-users text-purple-400"></i>
                                </div>
                                <span class="text-sm text-gray-400">Characters</span>
                            </div>
                            <p class="text-2xl font-bold text-white">{{ count($user->characters ?? []) }}</p>
                        </div>

                        <!-- Member Since Card -->
                        <div class="bg-gradient-to-br from-emerald-500/10 to-emerald-600/5 rounded-xl p-5 border border-purple-500/20 hover:border-emerald-500/40 transition-all duration-200 hover:transform hover:scale-105">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                                    <i class="fas fa-calendar text-emerald-400"></i>
                                </div>
                                <span class="text-sm text-gray-400">Member Since</span>
                            </div>
                            <p class="text-lg font-bold text-white">{{ $user->created_at->format('m/d/Y') }}</p>
                        </div>

                        <!-- Coins Card -->
                        <div class="bg-gradient-to-br from-amber-500/10 to-amber-600/5 rounded-xl p-5 border border-purple-500/20 hover:border-amber-500/40 transition-all duration-200 hover:transform hover:scale-105">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-amber-500/20 flex items-center justify-center">
                                    <i class="fas fa-coins text-amber-400"></i>
                                </div>
                                <span class="text-sm text-gray-400">Coins</span>
                            </div>
                            <p class="text-2xl font-bold text-white">{{ $user->dp }}</p>
                        </div>
                    </div>
                </div>
            </div>

             <!-- My Characters -->
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 overflow-hidden shadow-xl">
                <div class="p-6 border-b border-slate-700/50">
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">My Characters</h2>
                </div>
                
                <div class="p-6">
                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-slate-700/50">
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-400 uppercase tracking-wider">Name</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-400 uppercase tracking-wider">Level</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-400 uppercase tracking-wider">Class</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-400 uppercase tracking-wider">Realm</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700/30">
                                @forelse ($user->characters ?? [] as $character)
                                    <tr class="hover:bg-slate-700/20 transition-colors duration-200">
                                        <td class="px-4 py-4 flex items-center space-x-3">
                                            <img class="h-8 w-8 rounded-full" src="https://wow.zamimg.com/images/wow/icons/large/inv_misc_questionmark.jpg" alt="{{ $character->name }}">
                                            <span class="font-medium text-white">{{ $character->name }}</span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="px-2 py-1 text-xs font-medium text-yellow-400 bg-yellow-400/10 rounded-full">{{ $character->level }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-gray-300">{{ $character->class }}</td>
                                        <td class="px-4 py-4 text-gray-300">{{ $character->realm }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-20 h-20 rounded-full bg-slate-700/50 flex items-center justify-center mb-6">
                                                    <i class="fas fa-user-slash text-3xl text-gray-500"></i>
                                                </div>
                                                <h3 class="text-xl font-bold text-gray-300 mb-2">No characters available</h3>
                                                <p class="text-gray-500 mb-6">Create your first character to begin your adventure</p>
                                                <a href="{{ route('ucp.gameaccount.create') }}" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-medium rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg shadow-blue-500/25">
                                                    <i class="fas fa-plus mr-2"></i>
                                                    Create Character
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection