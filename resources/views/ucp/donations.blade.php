@extends('layouts.main')

@section('content')
<div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8 py-8 pt-24">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        @include('ucp.components.sidebar')

        <main class="lg:col-span-3 space-y-6">
                <!-- Stats Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Total Donated -->
                <div class="bg-gradient-to-br from-emerald-500/10 to-emerald-600/5 rounded-xl p-6 border border-emerald-500/20 hover:border-emerald-500/40 transition-all duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-xl text-emerald-400"></i>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">Total Donated</p>
                    <p class="text-3xl font-bold text-white">{{ $transactions->isEmpty() ? '0.00' : number_format($transactions->sum('amount'), 2) }}</p>
                </div>

                <!-- Total Coins -->
                <div class="bg-gradient-to-br from-amber-500/10 to-amber-600/5 rounded-xl p-6 border border-amber-500/20 hover:border-amber-500/40 transition-all duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 rounded-lg bg-amber-500/20 flex items-center justify-center">
                            <i class="fas fa-coins text-xl text-amber-400"></i>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">Total Coins Earned</p>
                    <p class="text-3xl font-bold text-white">{{ $transactions->isEmpty() ? '0' : number_format($transactions->sum('dp_awarded')) }}</p>
                </div>

                <!-- Transactions -->
                <div class="bg-gradient-to-br from-blue-500/10 to-blue-600/5 rounded-xl p-6 border border-blue-500/20 hover:border-blue-500/40 transition-all duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center">
                            <i class="fas fa-receipt text-xl text-blue-400"></i>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">Total Transactions</p>
                    <p class="text-3xl font-bold text-white">{{ $transactions->isEmpty() ? '0' : number_format($transactions->count()) }}</p>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 overflow-hidden shadow-xl">
                <div class="p-6 border-b border-slate-700/50 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">Transaction History</h2>
                        <p class="text-sm text-gray-400 mt-1">Manage and track all your donation transactions</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-700/30">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Transaction ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Coins</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Method</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/50">
                            @foreach ($transactions as $transaction)
                            <!-- Transaction 1 -->
                            <tr class="hover:bg-slate-700/20 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center">
                                            <i class="fas fa-hashtag text-blue-400"></i>
                                        </div>
                                        <span class="text-sm font-mono text-gray-300">{{ $transaction->transaction_id }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-300">{{ $transaction->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $transaction->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-emerald-400">${{ number_format($transaction->amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-coins text-amber-400"></i>
                                        <span class="text-sm font-semibold text-white">{{ number_format($transaction->dp_awarded, 0) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-300 text-capitalize">{{ ucfirst($transaction->gateway) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($transaction->status == 'completed')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-300 border border-green-500/30">
                                        <div class="w-1.5 h-1.5 bg-green-400 rounded-full mr-2"></div>
                                        Completed
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        <div class="w-1.5 h-1.5 bg-amber-400 rounded-full mr-2 animate-pulse"></div>
                                        Pending
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-gray-400 hover:text-blue-400 transition-colors duration-200">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-700/50 flex items-center justify-between">
                    <div class="text-sm text-gray-400">
                        Showing <span class="font-semibold text-white">1-5</span> of <span class="font-semibold text-white">18</span> transactions
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="px-4 py-2 bg-slate-700/50 text-gray-400 rounded-lg hover:bg-slate-700 hover:text-white transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <i class="fas fa-chevron-left mr-2"></i>
                            Previous
                        </button>
                        <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all duration-200">
                            1
                        </button>
                        <button class="px-4 py-2 bg-slate-700/50 text-gray-400 rounded-lg hover:bg-slate-700 hover:text-white transition-all duration-200">
                            2
                        </button>
                        <button class="px-4 py-2 bg-slate-700/50 text-gray-400 rounded-lg hover:bg-slate-700 hover:text-white transition-all duration-200">
                            3
                        </button>
                        <button class="px-4 py-2 bg-slate-700/50 text-gray-400 rounded-lg hover:bg-slate-700 hover:text-white transition-all duration-200">
                            Next
                            <i class="fas fa-chevron-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection