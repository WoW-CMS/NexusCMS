@extends('admin::layouts.app')

@section('title', 'Donation Transactions')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <h1 class="text-2xl font-bold text-gray-800">Donation Transactions</h1>
    <a href="{{ route('admin.donate.plans.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
        <i class="fas fa-arrow-left mr-1"></i> Plans
    </a>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.donate.transactions.index') }}"
          class="bg-white rounded-lg shadow p-4 mb-4 grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
        <div class="md:col-span-4">
            <label class="block text-xs font-medium text-gray-600 mb-1">Search</label>
            <input type="search" name="q" value="{{ request('q') }}"
                   placeholder="User name, email, transaction ID…"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1">Gateway</label>
            <select name="gateway" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All</option>
                @foreach(($gateways ?? []) as $gw)
                    <option value="{{ $gw }}" @selected(request('gateway') === $gw)>{{ ucfirst($gw) }}</option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All</option>
                @foreach(['completed', 'pending', 'failed', 'refunded'] as $st)
                    <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1">From</label>
            <input type="date" name="from" value="{{ request('from') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1">To</label>
            <input type="date" name="to" value="{{ request('to') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="md:col-span-12 flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                <i class="fas fa-filter mr-1"></i>Apply Filters
            </button>
            @if(request()->hasAny(['q', 'gateway', 'status', 'from', 'to']))
                <a href="{{ route('admin.donate.transactions.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">
                    <i class="fas fa-times mr-1"></i>Clear
                </a>
            @endif
            <span class="ml-auto text-sm text-gray-500">
                {{ $transactions->total() }} {{ Str::plural('transaction', $transactions->total()) }}
            </span>
        </div>
    </form>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Gateway</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">DP Awarded</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $tx)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-500 font-mono text-xs">{{ $tx->id }}</td>
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $tx->user?->name ?? '—' }}</td>
                        <td class="px-6 py-3 text-gray-600 capitalize">{{ $tx->gateway }}</td>
                        <td class="px-6 py-3 text-gray-700">${{ number_format($tx->amount, 2) }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ number_format($tx->dp_awarded) }} DP</td>
                        <td class="px-6 py-3">
                            @php
                                $colors = [
                                    'completed' => 'bg-green-100 text-green-800',
                                    'pending'   => 'bg-yellow-100 text-yellow-800',
                                    'failed'    => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium {{ $colors[$tx->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($tx->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-gray-500 text-xs">{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-400">No transactions yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</main>
@endsection
