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
            <!-- Bronze Package -->
            <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-8 hover:border-orange-600/50 transition-all duration-300">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-600 to-orange-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-medal text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Bronze Supporter</h3>
                    <div class="text-4xl font-bold text-orange-500 mb-2">$5</div>
                    <p class="text-slate-400 text-sm">One-time donation</p>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check text-orange-500 mt-1"></i>
                        <span class="text-slate-300">500 Donation Points</span>
                    </li>
                </ul>
                <button class="w-full py-3 bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 rounded-lg font-semibold transition-all duration-200 shadow-lg shadow-orange-900/30">
                    Donate Now
                </button>
            </div>

            <!-- Silver Package -->
            <div class="bg-slate-900/50 border-2 border-slate-400 rounded-xl p-8 hover:border-slate-300 transition-all duration-300 relative transform md:scale-105">
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-1 rounded-full text-sm font-semibold">MOST POPULAR</span>
                </div>
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-slate-400 to-slate-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-crown text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Silver Supporter</h3>
                    <div class="text-4xl font-bold text-slate-400 mb-2">$15</div>
                    <p class="text-slate-400 text-sm">One-time donation</p>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check text-slate-400 mt-1"></i>
                        <span class="text-slate-300">1,800 Donation Points</span>
                    </li>
                </ul>
                <button class="w-full py-3 bg-gradient-to-r from-slate-400 to-slate-600 hover:from-slate-500 hover:to-slate-700 rounded-lg font-semibold transition-all duration-200 shadow-lg shadow-slate-900/50">
                    Donate Now
                </button>
            </div>

            <!-- Gold Package -->
            <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-8 hover:border-yellow-600/50 transition-all duration-300">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-yellow-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-gem text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Gold Supporter</h3>
                    <div class="text-4xl font-bold text-yellow-500 mb-2">$30</div>
                    <p class="text-slate-400 text-sm">One-time donation</p>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check text-yellow-500 mt-1"></i>
                        <span class="text-slate-300">4,000 Donation Points</span>
                    </li>
                </ul>
                <button class="w-full py-3 bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 rounded-lg font-semibold transition-all duration-200 shadow-lg shadow-yellow-900/30">
                    Donate Now
                </button>
            </div>
        </div>

        <!-- Custom Amount Section -->
        <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-8 mb-16">
            <div class="max-w-2xl mx-auto text-center">
                <h3 class="text-2xl font-bold mb-4">Custom Donation Amount</h3>
                <p class="text-slate-400 mb-6">Choose your own amount to support the server</p>
                <div class="flex flex-col sm:flex-row gap-4 items-center justify-center">
                    <div class="relative flex-1 w-full sm:max-w-xs">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400 text-xl">$</span>
                        <input type="number" placeholder="Enter amount" min="1" class="w-full pl-10 pr-4 py-3 bg-slate-800 border border-slate-700 rounded-lg focus:outline-none focus:border-blue-500 text-white">
                    </div>
                    <button class="px-8 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition-all duration-200 shadow-lg shadow-blue-900/30 w-full sm:w-auto">
                        Donate
                    </button>
                </div>
                <p class="text-slate-500 text-sm mt-4">Conversion rate: $1 = 100 Donation Points</p>
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