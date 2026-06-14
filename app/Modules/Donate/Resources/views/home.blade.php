@extends('layouts.main')

@section('title', 'Donate')

@section('content')
    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                Support <span class="text-blue-500">NexusCMS</span>
            </h1>
            <p class="text-xl text-slate-400 max-w-3xl mx-auto">
                Your donations help us maintain high-quality servers, develop custom content, and keep the community thriving. Every contribution makes a difference.
            </p>
        </div>
    </div>

    <!-- Donation Packages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Donation Packages</h2>
            <p class="text-slate-400">Choose a package that suits you best</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 mb-16">
            @foreach(($plans ?? collect())->take(3) as $plan)
                <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-8 hover:border-blue-600/50 transition-all duration-300">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-gift text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">{{ $plan->name }}</h3>
                        <div class="text-4xl font-bold text-blue-500 mb-2">{{ $plan->formatted_amount }}</div>
                        <p class="text-slate-400 text-sm">{{ $plan->description }}</p>
                        @if($plan->is_promo)
                            <span class="inline-block mt-2 bg-red-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                PROMO +{{ $plan->extra_pct }}%
                            </span>
                        @endif
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-blue-500 mt-1"></i>
                            <span class="text-slate-300">{{ number_format($plan->dp_base) }} Donation Points</span>
                        </li>
                        @if($plan->extra_pct > 0)
                            <li class="flex items-start gap-2">
                                <i class="fas fa-star text-yellow-500 mt-1"></i>
                                <span class="text-slate-300">+{{ $plan->extra_pct }}% Bonus Points</span>
                            </li>
                        @endif
                    </ul>
                    @auth
                        <form method="POST" action="{{ route('donate.checkout') }}">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <input type="hidden" name="amount" value="{{ $plan->amount }}">
                            <input type="hidden" name="gateway" value="braintree">
                            <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 rounded-lg font-semibold transition-all duration-200 shadow-lg shadow-blue-900/30">
                                Donate Now
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block w-full py-3 bg-slate-700 hover:bg-slate-600 rounded-lg font-semibold transition-all duration-200 text-center">
                            Login to Donate
                        </a>
                    @endauth
                </div>
            @endforeach
        </div>

        <!-- All Plans Grid -->
        @if(($plans ?? collect())->count() > 3)
            <div class="mb-16">
                <h3 class="text-2xl font-bold mb-8 text-center">More Packages</h3>
                <div class="grid md:grid-cols-4 gap-6">
                    @foreach(($plans ?? collect())->skip(3) as $plan)
                        <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-6 hover:border-blue-600/50 transition-all duration-300">
                            <div class="text-center mb-4">
                                <h4 class="font-bold mb-2">{{ $plan->name }}</h4>
                                <div class="text-4xl font-bold text-blue-500 mb-2">{{ $plan->formatted_amount }}</div>
                                @if($plan->is_promo)
                                    <span class="inline-block bg-red-600 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                        PROMO +{{ $plan->extra_pct }}%
                                    </span>
                                @endif
                            </div>
                            <div class="text-center mb-4">
                                <span class="text-slate-300">{{ number_format($plan->dp_base) }} DP</span>
                            </div>
                            @auth
                                <form method="POST" action="{{ route('donate.checkout') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                    <input type="hidden" name="amount" value="{{ $plan->amount }}">
                                    <input type="hidden" name="gateway" value="braintree">
                                    <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition-all duration-200">
                                        Donate
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="block w-full py-2 bg-slate-700 hover:bg-slate-600 rounded-lg font-semibold transition-all duration-200 text-center">
                                    Login
                                </a>
                            @endauth
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Custom Amount Section -->
        <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-8 mb-16">
            <div class="max-w-2xl mx-auto text-center">
                <h3 class="text-2xl font-bold mb-4">Custom Donation Amount</h3>
                <p class="text-slate-400 mb-6">Choose your own amount to support the server</p>
                @auth
                    <form method="POST" action="{{ route('donate.checkout') }}" class="flex flex-col sm:flex-row gap-4 items-center justify-center">
                        @csrf
                        <div class="relative flex-1 w-full sm:max-w-xs">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400 text-xl">$</span>
                            <input name="amount" type="number" placeholder="Enter amount" min="1" class="w-full pl-10 pr-4 py-3 bg-slate-800 border border-slate-700 rounded-lg focus:outline-none focus:border-blue-500 text-white">
                        </div>
                        <div class="flex-1 w-full sm:max-w-xs">
                            <select name="gateway" class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white">
                                @foreach(($gateways ?? []) as $gw)
                                    <option value="{{ $gw['id'] }}">{{ $gw['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition-all duration-200 shadow-lg shadow-blue-900/30 w-full sm:w-auto">
                            Donate
                        </button>
                    </form>
                @else
                    <div class="flex flex-col sm:flex-row gap-4 items-center justify-center">
                        <div class="relative flex-1 w-full sm:max-w-xs">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400 text-xl">$</span>
                            <input disabled placeholder="Login required" class="w-full pl-10 pr-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-500">
                        </div>
                        <div class="flex-1 w-full sm:max-w-xs">
                            <select disabled class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-500">
                                <option>Select gateway</option>
                            </select>
                        </div>
                        <a href="{{ route('login') }}" class="px-8 py-3 bg-slate-700 hover:bg-slate-600 rounded-lg font-semibold transition-all duration-200 text-white w-full sm:w-auto">
                            Login to Donate
                        </a>
                    </div>
                @endauth
                <p class="text-slate-500 text-sm mt-4">Conversion rate: $1 = {{ config('donate.dp_rate') }} Donation Points</p>
            </div>
        </div>

        <!-- Donation Points Store Preview -->
        <div class="mb-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">What Can You Buy?</h2>
                <p class="text-slate-400">Spend your donation points on exclusive items and services</p>
            </div>

            <div class="grid md:grid-cols-4 gap-6">
                <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-6 hover:border-blue-500/50 transition-all duration-300">
                    <h4 class="font-bold mb-2">Epic Mount</h4>
                    <p class="text-slate-400 text-sm mb-4">Exclusive flying mount</p>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-500 font-semibold">1,500 DP</span>
                        <button class="text-sm text-slate-400 hover:text-white">View</button>
                    </div>
                </div>

                <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-6 hover:border-blue-500/50 transition-all duration-300">
                    <h4 class="font-bold mb-2">Transmog Set</h4>
                    <p class="text-slate-400 text-sm mb-4">Legendary appearance</p>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-500 font-semibold">2,000 DP</span>
                        <button class="text-sm text-slate-400 hover:text-white">View</button>
                    </div>
                </div>

                <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-6 hover:border-blue-500/50 transition-all duration-300">
                    <h4 class="font-bold mb-2">Name Change</h4>
                    <p class="text-slate-400 text-sm mb-4">Change character name</p>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-500 font-semibold">500 DP</span>
                        <button class="text-sm text-slate-400 hover:text-white">View</button>
                    </div>
                </div>

                <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-6 hover:border-blue-500/50 transition-all duration-300">
                    <h4 class="font-bold mb-2">Level Boost</h4>
                    <p class="text-slate-400 text-sm mb-4">Instant level 70</p>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-500 font-semibold">3,000 DP</span>
                        <button class="text-sm text-slate-400 hover:text-white">View</button>
                    </div>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="#" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-800 hover:bg-slate-700 rounded-lg font-semibold transition-all duration-200">
                    View Full Store
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-8">
            <h2 class="text-3xl font-bold mb-8 text-center">Donation FAQ</h2>
            <div class="space-y-4 max-w-3xl mx-auto">
                <div class="bg-slate-800/50 rounded-lg p-6">
                    <h4 class="font-bold mb-2 flex items-center gap-2">
                        <i class="fas fa-question-circle text-blue-500"></i>
                        How do I receive my donation points?
                    </h4>
                    <p class="text-slate-400 text-sm">Donation points are automatically added to your account within 5 minutes after a successful payment.</p>
                </div>
                <div class="bg-slate-800/50 rounded-lg p-6">
                    <h4 class="font-bold mb-2 flex items-center gap-2">
                        <i class="fas fa-question-circle text-blue-500"></i>
                        What payment methods do you accept?
                    </h4>
                    <p class="text-slate-400 text-sm">We accept PayPal, credit cards, and various cryptocurrency options for your convenience.</p>
                </div>
                <div class="bg-slate-800/50 rounded-lg p-6">
                    <h4 class="font-bold mb-2 flex items-center gap-2">
                        <i class="fas fa-question-circle text-blue-500"></i>
                        Can I get a refund?
                    </h4>
                    <p class="text-slate-400 text-sm">Donations are generally non-refundable. However, if you encounter technical issues, please contact our support team.</p>
                </div>
                <div class="bg-slate-800/50 rounded-lg p-6">
                    <h4 class="font-bold mb-2 flex items-center gap-2">
                        <i class="fas fa-question-circle text-blue-500"></i>
                        Are donations required to play?
                    </h4>
                    <p class="text-slate-400 text-sm">Absolutely not! NexusCMS is completely free to play. Donations help us improve the server but are entirely optional.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Thank You Section -->
    <div class="bg-gradient-to-r from-blue-900/20 to-purple-900/20 border-y border-slate-800 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <i class="fas fa-heart text-5xl text-red-500 mb-6"></i>
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Thank You for Your Support!</h2>
            <p class="text-slate-300 text-lg">
                Every donation, no matter the size, helps us maintain our servers, develop new content, and create the best possible experience for our community. We couldn't do this without supporters like you.
            </p>
        </div>
    </div>
@endsection