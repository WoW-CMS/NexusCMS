@extends('layouts.main')

@section('title', 'Store')

@section('content')
    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-5xl md:text-6xl font-bold mb-6">
                    Donation <span class="text-blue-500">Store</span>
                </h1>
                <p class="text-xl text-slate-400 max-w-3xl mx-auto mb-4">
                    Use your donation points to purchase exclusive items, services, and perks across all realms
                </p>
                <div class="inline-flex items-center gap-3 bg-slate-900/50 border border-slate-800 rounded-lg px-6 py-3">
                    <i class="fas fa-coins text-yellow-500 text-xl"></i>
                    <div class="text-left">
                        <div class="text-xs text-slate-400">Your Balance</div>
                        <div class="text-2xl font-bold text-yellow-500">2,450 DP</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Realm Selector -->
    @extends('layouts.main')

    @section('title', 'Store')

    @section('content')
        <div class="max-w-7xl mx-auto pt-24 pb-14 px-4 sm:px-6 lg:px-8">
            <div class="mb-8 bg-slate-900/60 border border-slate-800 rounded-2xl p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold mb-2">Donation Store</h1>
                        <p class="text-slate-300">Purchase account services and rewards using your Donation Points.</p>
                    </div>
                    <div class="rounded-xl bg-slate-950/80 border border-slate-700 px-6 py-4 min-w-[180px]">
                        <p class="text-xs uppercase tracking-widest text-slate-400">Current Balance</p>
                        <p class="text-3xl font-bold text-yellow-400">{{ number_format($balance) }} DP</p>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-700/60 bg-green-900/30 text-green-200 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-lg border border-red-700/60 bg-red-900/30 text-red-200 px-4 py-3">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-700/60 bg-red-900/30 text-red-200 px-4 py-3">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($linkedAccounts->isEmpty())
                <div class="rounded-xl border border-amber-700/60 bg-amber-900/20 text-amber-100 p-6">
                    You do not have linked game accounts yet. Link at least one realm account before making purchases.
                </div>
            @else
                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach ($products as $key => $product)
                        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 flex flex-col gap-4">
                            <div>
                                <h3 class="text-xl font-semibold">{{ $product['name'] }}</h3>
                                <p class="text-slate-400 mt-1">{{ $product['description'] }}</p>
                            </div>

                            <div class="text-2xl font-bold text-blue-400">{{ number_format($product['cost']) }} DP</div>

                            <form action="{{ route('store.purchase') }}" method="POST" class="space-y-3 mt-auto">
                                @csrf
                                <input type="hidden" name="product" value="{{ $key }}">

                                <div>
                                    <label for="realm_{{ $key }}" class="block text-sm text-slate-300 mb-1">Realm</label>
                                    <select id="realm_{{ $key }}" name="realm_id" class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-slate-100" required>
                                        <option value="">Select a realm</option>
                                        @foreach ($linkedAccounts as $linked)
                                            <option value="{{ $linked->realm->id }}">{{ $linked->realm->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @if ($product['requires_character'])
                                    <div>
                                        <label for="character_{{ $key }}" class="block text-sm text-slate-300 mb-1">Character Name</label>
                                        <input
                                            id="character_{{ $key }}"
                                            name="character_name"
                                            type="text"
                                            maxlength="12"
                                            class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-slate-100"
                                            placeholder="Your character"
                                            required
                                        >
                                    </div>
                                @endif

                                <button type="submit" class="w-full rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 transition-colors">
                                    Buy Now
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endsection
        <!-- Products Grid -->
