@extends('admin::layouts.app')

@section('title', 'Donation Plans')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <h1 class="text-2xl font-bold text-gray-800">Donation Plans</h1>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.donate.transactions.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700 font-medium">
            <i class="fas fa-list mr-1"></i> Transactions
        </a>
        <a href="{{ route('admin.donate.plans.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <i class="fas fa-plus"></i> New Plan
        </a>
    </div>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">DP Base</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Bonus %</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Promo</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Active</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($plans as $plan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $plan->name }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $plan->formatted_amount }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ number_format($plan->dp_base) }} DP</td>
                        <td class="px-6 py-3 text-gray-500">+{{ $plan->extra_pct }}%</td>
                        <td class="px-6 py-3">
                            @if($plan->is_promo)
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Promo</span>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            @if($plan->active)
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-gray-500">{{ $plan->sort_order }}</td>
                        <td class="px-6 py-3 text-right space-x-2">
                            <a href="{{ route('admin.donate.plans.edit', $plan) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                            <form method="POST" action="{{ route('admin.donate.plans.destroy', $plan) }}" class="inline"
                                  onsubmit="return confirm('Delete this plan?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-400">No donation plans yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection
