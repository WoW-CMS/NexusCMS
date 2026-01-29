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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-globe text-blue-500 text-xl"></i>
                    <div>
                        <h3 class="font-bold">Select Realm</h3>
                        <p class="text-sm text-slate-400">Choose which realm to purchase items for</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition-all duration-200 shadow-lg shadow-blue-900/30">
                        <i class="fas fa-server mr-2"></i>All Realms
                    </button>
                    <button class="px-6 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg font-semibold transition-all duration-200">
                        <i class="fas fa-fire mr-2 text-red-500"></i>Realm 1
                    </button>
                    <button class="px-6 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg font-semibold transition-all duration-200">
                        <i class="fas fa-bolt mr-2 text-yellow-500"></i>Realm 2
                    </button>
                    <button class="px-6 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg font-semibold transition-all duration-200">
                        <i class="fas fa-snowflake mr-2 text-cyan-500"></i>Realm 3
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="flex flex-col md:flex-row gap-4 mb-8">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    <input type="text" placeholder="Search items..." class="w-full pl-12 pr-4 py-3 bg-slate-900/50 border border-slate-800 rounded-lg focus:outline-none focus:border-blue-500 text-white">
                </div>
            </div>
            <select class="px-4 py-3 bg-slate-900/50 border border-slate-800 rounded-lg focus:outline-none focus:border-blue-500 text-white">
                <option>All Categories</option>
                <option>Mounts</option>
                <option>Pets</option>
                <option>Transmog</option>
                <option>Services</option>
                <option>Consumables</option>
                <option>Miscellaneous</option>
            </select>
            <select class="px-4 py-3 bg-slate-900/50 border border-slate-800 rounded-lg focus:outline-none focus:border-blue-500 text-white">
                <option>Sort by: Featured</option>
                <option>Price: Low to High</option>
                <option>Price: High to Low</option>
                <option>Newest</option>
                <option>Most Popular</option>
            </select>
        </div>

        <!-- Category Tabs -->
        <div class="flex flex-wrap gap-3 mb-8">
            <button class="px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition-all duration-200">
                <i class="fas fa-star mr-2"></i>Featured
            </button>
            <button class="px-5 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg font-semibold transition-all duration-200">
                <i class="fas fa-horse mr-2"></i>Mounts
            </button>
            <button class="px-5 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg font-semibold transition-all duration-200">
                <i class="fas fa-paw mr-2"></i>Pets
            </button>
            <button class="px-5 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg font-semibold transition-all duration-200">
                <i class="fas fa-tshirt mr-2"></i>Transmog
            </button>
            <button class="px-5 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg font-semibold transition-all duration-200">
                <i class="fas fa-cogs mr-2"></i>Services
            </button>
            <button class="px-5 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg font-semibold transition-all duration-200">
                <i class="fas fa-flask mr-2"></i>Consumables
            </button>
        </div>

        <!-- Featured Banner -->
        <div class="bg-gradient-to-r from-purple-900/50 to-blue-900/50 border border-purple-700/50 rounded-xl p-8 mb-12">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div class="w-32 h-32 bg-slate-800 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dragon text-6xl text-purple-500"></i>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <span class="inline-block px-3 py-1 bg-purple-600 rounded-full text-xs font-semibold mb-2">LIMITED TIME</span>
                    <h3 class="text-2xl md:text-3xl font-bold mb-2">Celestial Dragon Mount</h3>
                    <p class="text-slate-300 mb-4">Exclusive flying mount with special effects - Only available this month!</p>
                    <div class="flex items-center gap-4 justify-center md:justify-start">
                        <span class="text-3xl font-bold text-purple-400">2,500 DP</span>
                        <button class="px-6 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg font-semibold transition-all duration-200 shadow-lg shadow-purple-900/30">
                            Purchase Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">
            <!-- Product Card 1 -->
            <div class="bg-slate-900/50 border border-slate-800 rounded-xl overflow-hidden hover:border-blue-500/50 transition-all duration-300 group">
                <div class="relative">
                    <div class="w-full aspect-square bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                        <i class="fas fa-dragon text-6xl text-blue-500 group-hover:scale-110 transition-transform duration-300"></i>
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="px-2 py-1 bg-blue-600 rounded text-xs font-semibold">NEW</span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <h4 class="font-bold text-sm">Swift Spectral Tiger</h4>
                        <button class="text-slate-400 hover:text-yellow-500 transition-colors">
                            <i class="fas fa-star"></i>
                        </button>
                    </div>
                    <p class="text-slate-400 text-xs mb-3">Epic Flying Mount</p>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-500 font-bold">1,800 DP</span>
                        <button class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded text-sm font-semibold transition-all duration-200">
                            Buy
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card 2 -->
            <div class="bg-slate-900/50 border border-slate-800 rounded-xl overflow-hidden hover:border-blue-500/50 transition-all duration-300 group">
                <div class="relative">
                    <div class="w-full aspect-square bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                        <i class="fas fa-cat text-6xl text-purple-500 group-hover:scale-110 transition-transform duration-300"></i>
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="px-2 py-1 bg-red-600 rounded text-xs font-semibold">HOT</span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <h4 class="font-bold text-sm">Mini Phoenix Pet</h4>
                        <button class="text-slate-400 hover:text-yellow-500 transition-colors">
                            <i class="fas fa-star"></i>
                        </button>
                    </div>
                    <p class="text-slate-400 text-xs mb-3">Companion Pet</p>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-500 font-bold">500 DP</span>
                        <button class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded text-sm font-semibold transition-all duration-200">
                            Buy
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card 3 -->
            <div class="bg-slate-900/50 border border-slate-800 rounded-xl overflow-hidden hover:border-blue-500/50 transition-all duration-300 group">
                <div class="w-full aspect-square bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                    <i class="fas fa-vest text-6xl text-yellow-500 group-hover:scale-110 transition-transform duration-300"></i>
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <h4 class="font-bold text-sm">Legendary Transmog Set</h4>
                        <button class="text-slate-400 hover:text-yellow-500 transition-colors">
                            <i class="fas fa-star"></i>
                        </button>
                    </div>
                    <p class="text-slate-400 text-xs mb-3">Full Armor Set</p>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-500 font-bold">2,200 DP</span>
                        <button class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded text-sm font-semibold transition-all duration-200">
                            Buy
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card 4 -->
            <div class="bg-slate-900/50 border border-slate-800 rounded-xl overflow-hidden hover:border-blue-500/50 transition-all duration-300 group">
                <div class="w-full aspect-square bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                    <i class="fas fa-level-up-alt text-6xl text-green-500 group-hover:scale-110 transition-transform duration-300"></i>
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <h4 class="font-bold text-sm">Level 80 Boost</h4>
                        <button class="text-slate-400 hover:text-yellow-500 transition-colors">
                            <i class="fas fa-star"></i>
                        </button>
                    </div>
                    <p class="text-slate-400 text-xs mb-3">Character Service</p>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-500 font-bold">3,500 DP</span>
                        <button class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded text-sm font-semibold transition-all duration-200">
                            Buy
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card 5 -->
            <div class="bg-slate-900/50 border border-slate-800 rounded-xl overflow-hidden hover:border-blue-500/50 transition-all duration-300 group">
                <div class="w-full aspect-square bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                    <i class="fas fa-user-edit text-6xl text-cyan-500 group-hover:scale-110 transition-transform duration-300"></i>
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <h4 class="font-bold text-sm">Name Change</h4>
                        <button class="text-slate-400 hover:text-yellow-500 transition-colors">
                            <i class="fas fa-star"></i>
                        </button>
                    </div>
                    <p class="text-slate-400 text-xs mb-3">Character Service</p>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-500 font-bold">600 DP</span>
                        <button class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded text-sm font-semibold transition-all duration-200">
                            Buy
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card 6 -->
            <div class="bg-slate-900/50 border border-slate-800 rounded-xl overflow-hidden hover:border-blue-500/50 transition-all duration-300 group">
                <div class="w-full aspect-square bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                    <i class="fas fa-exchange-alt text-6xl text-orange-500 group-hover:scale-110 transition-transform duration-300"></i>
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <h4 class="font-bold text-sm">Faction Change</h4>
                        <button class="text-slate-400 hover:text-yellow-500 transition-colors">
                            <i class="fas fa-star"></i>
                        </button>
                    </div>
                    <p class="text-slate-400 text-xs mb-3">Character Service</p>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-500 font-bold">1,200 DP</span>
                        <button class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded text-sm font-semibold transition-all duration-200">
                            Buy
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card 7 -->
            <div class="bg-slate-900/50 border border-slate-800 rounded-xl overflow-hidden hover:border-blue-500/50 transition-all duration-300 group">
                <div class="w-full aspect-square bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                    <i class="fas fa-users text-6xl text-pink-500 group-hover:scale-110 transition-transform duration-300"></i>
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <h4 class="font-bold text-sm">Race Change</h4>
                        <button class="text-slate-400 hover:text-yellow-500 transition-colors">
                            <i class="fas fa-star"></i>
                        </button>
                    </div>
                    <p class="text-slate-400 text-xs mb-3">Character Service</p>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-500 font-bold">1,000 DP</span>
                        <button class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded text-sm font-semibold transition-all duration-200">
                            Buy
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card 8 -->
            <div class="bg-slate-900/50 border border-slate-800 rounded-xl overflow-hidden hover:border-blue-500/50 transition-all duration-300 group">
                <div class="w-full aspect-square bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                    <i class="fas fa-flask text-6xl text-red-500 group-hover:scale-110 transition-transform duration-300"></i>
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <h4 class="font-bold text-sm">XP Boost Potion (x10)</h4>
                        <button class="text-slate-400 hover:text-yellow-500 transition-colors">
                            <i class="fas fa-star"></i>
                        </button>
                    </div>
                    <p class="text-slate-400 text-xs mb-3">Consumable</p>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-500 font-bold">300 DP</span>
                        <button onclick="document.getElementById('purchaseModal').classList.remove('hidden')" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded text-sm font-semibold transition-all duration-200">
                            Buy
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center items-center gap-2">
            <button class="px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg font-semibold transition-all duration-200">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition-all duration-200">1</button>
            <button class="px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg font-semibold transition-all duration-200">2</button>
            <button class="px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg font-semibold transition-all duration-200">3</button>
            <span class="px-2 text-slate-400">...</span>
            <button class="px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg font-semibold transition-all duration-200">10</button>
            <button class="px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg font-semibold transition-all duration-200">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="bg-gradient-to-r from-blue-900/20 to-purple-900/20 border-y border-slate-800 py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div>
                    <i class="fas fa-shield-alt text-4xl text-blue-500 mb-4"></i>
                    <h3 class="font-bold mb-2">Instant Delivery</h3>
                    <p class="text-slate-400 text-sm">All items are delivered automatically to your account within minutes</p>
                </div>
                <div>
                    <i class="fas fa-sync text-4xl text-green-500 mb-4"></i>
                    <h3 class="font-bold mb-2">Multi-Realm Support</h3>
                    <p class="text-slate-400 text-sm">Purchase once and use across all realms or choose specific realms</p>
                </div>
                <div>
                    <i class="fas fa-headset text-4xl text-purple-500 mb-4"></i>
                    <h3 class="font-bold mb-2">24/7 Support</h3>
                    <p class="text-slate-400 text-sm">Our support team is always ready to help with any questions</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
<div id="purchaseModal" class="hidden fixed inset-0 bg-black/70 z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div role="dialog" aria-modal="true" class="bg-slate-900 border border-slate-800 rounded-xl max-w-md w-full">
            <div class="flex items-center justify-between p-6 border-b border-slate-800">
                <h3 class="text-xl font-bold">Confirm Purchase</h3>
                <button class="px-3 py-2 bg-slate-800 rounded">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <div class="font-bold mb-1">Swift Spectral Tiger</div>
                    <div class="text-slate-400 text-sm">Epic Flying Mount</div>
                </div>
                <div class="bg-slate-800 rounded-lg p-4 mb-4">
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-400">Price</span>
                        <span class="font-bold">1,800 DP</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Your Balance</span>
                        <span class="font-bold text-yellow-500">2,450 DP</span>
                    </div>
                    <div class="border-t border-slate-700 my-3"></div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">After Purchase</span>
                        <span class="font-bold text-green-500">650 DP</span>
                    </div>
                </div>
                <label class="block mb-4">
                    <span class="text-sm text-slate-400 mb-2 block">Select Character</span>
                    <select class="w-full px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white">
                        <option>Character 1 - Level 80</option>
                        <option>Character 2 - Level 70</option>
                        <option>Character 3 - Level 60</option>
                    </select>
                </label>
            </div>
            <div class="flex gap-3 p-6 border-t border-slate-800">
                <button class="flex-1 px-4 py-2 bg-slate-800 rounded">Cancel</button>
                <button class="flex-1 px-4 py-2 bg-blue-600 rounded text-white">Confirm Purchase</button>
            </div>
        </div>
    </div>
</div>
@endsection
