@extends('layouts.main')

@section('title', 'Donation Receipt')

@section('content')
<div class="max-w-3xl mx-auto pt-24 pb-16 px-4">
    <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">Donation Receipt</h2>
            <button onclick="window.print()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-white font-semibold">
                Print / Save as PDF
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="text-sm text-slate-400">Receipt ID</div>
                <div class="font-semibold text-white">{{ $tx->id }}</div>
            </div>
            <div>
                <div class="text-sm text-slate-400">Date</div>
                <div class="font-semibold text-white">{{ $tx->created_at }}</div>
            </div>
            <div>
                <div class="text-sm text-slate-400">Gateway</div>
                <div class="font-semibold text-white capitalize">{{ $tx->gateway }}</div>
            </div>
            <div>
                <div class="text-sm text-slate-400">Transaction ID</div>
                <div class="font-semibold text-white">{{ $tx->transaction_id ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="text-sm text-slate-400">Amount</div>
                <div class="font-semibold text-white">${{ $tx->amount }} {{ $tx->currency }}</div>
            </div>
            <div>
                <div class="text-sm text-slate-400">DP Awarded</div>
                <div class="font-semibold text-blue-400">{{ number_format($tx->dp_awarded) }}</div>
            </div>
            <div>
                <div class="text-sm text-slate-400">Status</div>
                <div class="font-semibold {{ $tx->status === 'completed' ? 'text-green-400' : 'text-yellow-400' }}">{{ ucfirst($tx->status) }}</div>
            </div>
            <div>
                <div class="text-sm text-slate-400">User</div>
                <div class="font-semibold text-white">{{ Auth::user()->name }}</div>
            </div>
        </div>
        <div class="mt-8 text-sm text-slate-500">
            Keep this receipt as proof of your donation. Thank you for your support.
        </div>
    </div>
</div>
@endsection

