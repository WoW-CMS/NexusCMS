@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-900 to-gray-950 pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            @include('ucp.components.sidebar')

            <div class="flex-1 space-y-6">
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-900 p-6 shadow-xl transition-all duration-300 hover:bg-gray-800/70 hover:border-blue-500/20 hover:shadow-2xl hover:shadow-blue-500/5">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">
                            Account Overview
                        </h2>
                        <span class="px-3 py-1 text-sm font-medium text-emerald-400 bg-emerald-400/10 rounded-full border border-emerald-400/20">
                            Active Account
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-900 transition-all duration-300 hover:bg-gray-900/70 hover:border-blue-500/20 hover:shadow-lg hover:shadow-blue-500/5 hover:transform hover:scale-[1.02]">
                            <div class="flex items-center space-x-3 text-gray-400 mb-2">
                                <i class="fas fa-shield-alt w-5 h-5 text-blue-400"></i>
                                <span>Status</span>
                            </div>
                            <div class="text-xl font-bold text-white">Verified</div>
                        </div>
                        
                        <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-900 transition-all duration-300 hover:bg-gray-900/70 hover:border-blue-500/20 hover:shadow-lg hover:shadow-blue-500/5 hover:transform hover:scale-[1.02]">
                            <div class="flex items-center space-x-3 text-gray-400 mb-2">
                                <i class="fas fa-users w-5 h-5 text-purple-400"></i>
                                <span>Characters</span>
                            </div>
                            <div class="text-xl font-bold text-white">
                                {{ count($user->characters ?? []) }}
                            </div>
                        </div>
                        
                        <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-900 transition-all duration-300 hover:bg-gray-900/70 hover:border-purple-500/20 hover:shadow-lg hover:shadow-purple-500/5 hover:transform hover:scale-[1.02]">
                            <div class="flex items-center space-x-3 text-gray-400 mb-2">
                                <i class="fas fa-clock w-5 h-5 text-emerald-400"></i>
                                <span>Member Since</span>
                            </div>
                            <div class="text-xl font-bold text-white">
                                {{ $user->created_at->format('m/d/Y') }}
                            </div>
                        </div>
                        
                        <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-900 transition-all duration-300 hover:bg-gray-900/70 hover:border-emerald-500/20 hover:shadow-lg hover:shadow-emerald-500/5 hover:transform hover:scale-[1.02]">
                            <div class="flex items-center space-x-3 text-gray-400 mb-2">
                                <i class="fas fa-coins w-5 h-5 text-yellow-400"></i>
                                <span>Coins</span>
                            </div>
                            <div class="text-xl font-bold text-white">
                                {{ $user->coins ?? 0 }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-900 p-6 shadow-xl transition-all duration-300 hover:bg-gray-800/70 hover:border-blue-500/20 hover:shadow-2xl hover:shadow-blue-500/5">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">
                            My Characters
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-gray-400 border-b border-gray-700">
                                    <th class="pb-4 font-medium">Name</th>
                                    <th class="pb-4 font-medium">Level</th>
                                    <th class="pb-4 font-medium">Class</th>
                                    <th class="pb-4 font-medium">Realm</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @forelse ($user->characters ?? [] as $character)
                                    <tr class="text-gray-300 hover:bg-gray-800/30 transition-colors duration-200">
                                        <td class="py-4">
                                            <div class="flex items-center space-x-3">
                                                <img class="h-8 w-8 rounded-full" src="https://wow.zamimg.com/images/wow/icons/large/inv_misc_questionmark.jpg" alt="{{ $character->name }}">
                                                <span class="font-medium">{{ $character->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4">
                                            <span class="px-2 py-1 text-xs font-medium text-yellow-400 bg-yellow-400/10 rounded-full">
                                                Level {{ $character->level }}
                                            </span>
                                        </td>
                                        <td class="py-4">{{ $character->class }}</td>
                                        <td class="py-4">{{ $character->realm }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-gray-400">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-user-slash text-4xl mb-3"></i>
                                                <p class="text-lg">No characters available</p>
                                                <p class="text-sm text-gray-500">Create your first character to begin your adventure</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection