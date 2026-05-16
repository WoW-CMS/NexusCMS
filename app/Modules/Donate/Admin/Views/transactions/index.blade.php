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
