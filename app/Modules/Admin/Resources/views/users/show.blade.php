@extends('admin::layouts.app')

@section('title', 'User Details')

@section('content')
<div class="min-h-screen bg-gray-50">
    @php
        $userStatus = $user->status ?? 'unknown';
        $donationCount = $donationTransactions->count();
        $donationTotal = $donationTransactions->sum('amount');
        $awardedTotal = $donationTransactions->sum('dp_awarded');
    @endphp

    <div class="max-w-6xl mx-auto px-6 py-8 space-y-6">
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-r from-slate-900 via-slate-800 to-blue-900 p-6 md:p-8 text-white">
            <div class="absolute -top-16 -right-16 w-56 h-56 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -bottom-20 -left-16 w-64 h-64 rounded-full bg-blue-300/10 blur-2xl"></div>

            <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center text-lg font-semibold">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-blue-200">User Profile</p>
                        <h1 class="mt-1 text-2xl md:text-3xl font-semibold">{{ $user->name }}</h1>
                        <p class="mt-1 text-sm text-blue-100 break-all">{{ $user->email }}</p>
                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border border-white/25 bg-white/10">
                                ID #{{ $user->id }}
                            </span>
                            @if($userStatus === 'active')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-300/20 text-emerald-100 border border-emerald-200/30">Active</span>
                            @elseif($userStatus === 'banned')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-rose-300/20 text-rose-100 border border-rose-200/30">Banned</span>
                            @elseif($userStatus === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-300/20 text-amber-100 border border-amber-200/30">Pending</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-300/20 text-slate-100 border border-slate-200/30">{{ ucfirst($userStatus) }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium bg-white text-slate-900 rounded-lg hover:bg-slate-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit User
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white rounded-lg border border-white/30 hover:bg-white/10">
                        Back to Users
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <p class="text-xs uppercase tracking-wider text-gray-500">Donation Points</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($user->dp ?? 0) }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <p class="text-xs uppercase tracking-wider text-gray-500">Vote Points</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($user->vp ?? 0) }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <p class="text-xs uppercase tracking-wider text-gray-500">Donations</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $donationCount }}</p>
                <p class="text-xs text-gray-500 mt-1">Total awarded: {{ number_format($awardedTotal) }} DP</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <p class="text-xs uppercase tracking-wider text-gray-500">Total Donated</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format((float) $donationTotal, 2) }} USD</p>
                <p class="text-xs text-gray-500 mt-1">Across all gateways</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-sm font-semibold tracking-wide text-gray-900 uppercase">Account Information</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500">Registered</p>
                            <p class="mt-1 text-sm font-medium text-gray-900">{{ optional($user->created_at)->format('M d, Y H:i') ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500">Last Update</p>
                            <p class="mt-1 text-sm font-medium text-gray-900">{{ optional($user->updated_at)->format('M d, Y H:i') ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs uppercase tracking-wider text-gray-500">Assigned Roles</p>
                            @if($user->roles->isNotEmpty())
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-lg bg-blue-50 text-blue-700 border border-blue-100">{{ $role->name }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="mt-2 text-sm text-gray-500">No roles assigned.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                        <div>
                            <h2 class="text-sm font-semibold tracking-wide text-gray-900 uppercase">User Transactions</h2>
                            <p class="text-xs text-gray-500 mt-1">Donation history and placeholder store activity</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-medium w-fit">
                            Latest activity
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
                            <div class="space-y-3">
                                <h3 class="text-sm font-semibold text-gray-900">Donations</h3>
                                @forelse($donationTransactions as $transaction)
                                    <div class="rounded-xl border border-gray-200 p-4 hover:border-blue-200 hover:bg-blue-50/30 transition-colors">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ strtoupper($transaction->gateway) }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ optional($transaction->created_at)->format('M d, Y H:i') ?? '-' }}</p>
                                            </div>
                                            @if($transaction->status === 'completed')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Completed</span>
                                            @elseif($transaction->status === 'pending')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Pending</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">{{ ucfirst($transaction->status) }}</span>
                                            @endif
                                        </div>
                                        <div class="mt-3 grid grid-cols-2 gap-3">
                                            <div class="rounded-lg bg-white border border-gray-200 px-3 py-2">
                                                <p class="text-[11px] uppercase tracking-wider text-gray-500">Amount</p>
                                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ number_format((float) $transaction->amount, 2) }} {{ $transaction->currency }}</p>
                                            </div>
                                            <div class="rounded-lg bg-white border border-gray-200 px-3 py-2">
                                                <p class="text-[11px] uppercase tracking-wider text-gray-500">DP Awarded</p>
                                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ number_format($transaction->dp_awarded) }}</p>
                                            </div>
                                        </div>
                                        <p class="mt-3 text-[11px] text-gray-500 break-all">Tx: {{ $transaction->transaction_id ?? 'N/A' }}</p>
                                    </div>
                                @empty
                                    <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center">
                                        <p class="text-sm font-medium text-gray-700">No donation transactions</p>
                                        <p class="text-xs text-gray-500 mt-1">There is no payment history for this user yet.</p>
                                    </div>
                                @endforelse
                            </div>

                            <div class="space-y-3">
                                <h3 class="text-sm font-semibold text-gray-900">Store</h3>
                                <div class="rounded-xl border border-dashed border-gray-300 bg-gradient-to-b from-gray-50 to-white p-4">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">Placeholder: Mount Purchase</p>
                                            <p class="text-xs text-gray-500 mt-1">Apr 21, 2026 19:40</p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Placeholder</span>
                                    </div>
                                    <p class="text-sm text-gray-700 mt-3">Item: Spectral Tiger</p>
                                    <p class="text-sm text-gray-700">Cost: 50 DP</p>
                                </div>
                                <div class="rounded-xl border border-dashed border-gray-300 bg-gradient-to-b from-gray-50 to-white p-4">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">Placeholder: Character Service</p>
                                            <p class="text-xs text-gray-500 mt-1">Apr 20, 2026 15:10</p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Placeholder</span>
                                    </div>
                                    <p class="text-sm text-gray-700 mt-3">Service: Faction Change</p>
                                    <p class="text-sm text-gray-700">Cost: 30 DP</p>
                                </div>
                                <div class="rounded-xl border border-dashed border-gray-300 bg-gradient-to-b from-gray-50 to-white p-4">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">Placeholder: Bundle</p>
                                            <p class="text-xs text-gray-500 mt-1">Apr 19, 2026 10:05</p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Placeholder</span>
                                    </div>
                                    <p class="text-sm text-gray-700 mt-3">Bundle: PvP Starter Kit</p>
                                    <p class="text-sm text-gray-700">Cost: 20 DP</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="sticky top-6 rounded-xl border border-gray-200 bg-white overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Quick Actions</h3>
                        <p class="text-xs text-gray-500 mt-1">Manage this account quickly</p>
                    </div>
                    <div class="p-4 space-y-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="block w-full px-4 py-3 rounded-lg text-sm font-semibold text-center bg-blue-600 text-white hover:bg-blue-700">Edit User</a>
                        <a href="{{ route('admin.users.index') }}" class="block w-full px-4 py-3 rounded-lg text-sm font-medium text-center border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">Back to Users</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
