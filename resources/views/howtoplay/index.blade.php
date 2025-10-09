@extends('layouts.main')

@section('title', 'How to Play')

@section('content')
<!-- Hero Section -->
<section class="bg-hero-pattern py-32 relative">
    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-gray-900"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <div class="inline-block mb-4 px-6 py-2 bg-blue-600 bg-opacity-20 backdrop-filter backdrop-blur-lg rounded-full border border-blue-500">
                <span class="text-blue-400 font-semibold text-sm uppercase tracking-wider">Start Your Adventure</span>
            </div>
            <h1 class="text-6xl md:text-7xl font-bold mb-6 text-shadow">
                How to <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-600">Play</span>
            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed text-shadow">
                Join thousands of players in the ultimate World of Warcraft experience. Follow these simple steps to begin your epic journey.
            </p>
            <div class="mt-10 flex justify-center gap-4">
                <a href="#requirements" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                    <i class="fas fa-rocket mr-2"></i>Get Started
                </a>
                <a href="#faq" class="bg-gray-800 bg-opacity-50 backdrop-filter backdrop-blur-lg hover:bg-gray-700 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 border border-gray-700 hover:border-gray-600">
                    <i class="fas fa-question-circle mr-2"></i>FAQ
                </a>
            </div>
        </div>
    </div>
</section>

<!-- System Requirements -->
<section id="requirements" class="py-20 bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">System Requirements</h2>
            <p class="text-gray-400 text-lg">Make sure your system meets these requirements</p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-8" x-data="{ tab: 'minimum' }">
            <div class="bg-gray-800 rounded-2xl p-8 border border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-yellow-400">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Minimum
                    </h3>
                    <span class="px-4 py-1 bg-yellow-600 bg-opacity-20 text-yellow-400 rounded-full text-sm font-semibold">Required</span>
                </div>
                <div class="space-y-4">
                    <div class="requirement-badge p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-microchip text-blue-400 text-xl mt-1 mr-3"></i>
                            <div>
                                <div class="font-semibold text-white mb-1">Processor</div>
                                <div class="text-gray-400 text-sm">Intel Core 2 Duo E6600 / AMD Phenom X3 8750</div>
                            </div>
                        </div>
                    </div>
                    <div class="requirement-badge p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-memory text-green-400 text-xl mt-1 mr-3"></i>
                            <div>
                                <div class="font-semibold text-white mb-1">Memory</div>
                                <div class="text-gray-400 text-sm">4 GB RAM</div>
                            </div>
                        </div>
                    </div>
                    <div class="requirement-badge p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-desktop text-purple-400 text-xl mt-1 mr-3"></i>
                            <div>
                                <div class="font-semibold text-white mb-1">Graphics</div>
                                <div class="text-gray-400 text-sm">NVIDIA GeForce 8800 GT / AMD Radeon HD 4850</div>
                            </div>
                        </div>
                    </div>
                    <div class="requirement-badge p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-hdd text-orange-400 text-xl mt-1 mr-3"></i>
                            <div>
                                <div class="font-semibold text-white mb-1">Storage</div>
                                <div class="text-gray-400 text-sm">45 GB available space</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-2xl p-8 border border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-green-400">
                        <i class="fas fa-check-circle mr-2"></i>Recommended
                    </h3>
                    <span class="px-4 py-1 bg-green-600 bg-opacity-20 text-green-400 rounded-full text-sm font-semibold">Optimal</span>
                </div>
                <div class="space-y-4">
                    <div class="requirement-badge p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-microchip text-blue-400 text-xl mt-1 mr-3"></i>
                            <div>
                                <div class="font-semibold text-white mb-1">Processor</div>
                                <div class="text-gray-400 text-sm">Intel Core i5-3450 / AMD FX 8300</div>
                            </div>
                        </div>
                    </div>
                    <div class="requirement-badge p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-memory text-green-400 text-xl mt-1 mr-3"></i>
                            <div>
                                <div class="font-semibold text-white mb-1">Memory</div>
                                <div class="text-gray-400 text-sm">8 GB RAM</div>
                            </div>
                        </div>
                    </div>
                    <div class="requirement-badge p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-desktop text-purple-400 text-xl mt-1 mr-3"></i>
                            <div>
                                <div class="font-semibold text-white mb-1">Graphics</div>
                                <div class="text-gray-400 text-sm">NVIDIA GeForce GTX 760 / AMD Radeon RX 560</div>
                            </div>
                        </div>
                    </div>
                    <div class="requirement-badge p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-hdd text-orange-400 text-xl mt-1 mr-3"></i>
                            <div>
                                <div class="font-semibold text-white mb-1">Storage</div>
                                <div class="text-gray-400 text-sm">70 GB SSD available space</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Installation Steps -->
<section class="py-20 bg-gray-800">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">Installation Guide</h2>
            <p class="text-gray-400 text-lg">Follow these steps to get started in minutes</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Step 1 -->
            <div class="step-card bg-gray-900 rounded-2xl p-6 border border-gray-700">
                <div class="flex flex-col items-center text-center h-full">
                    <div class="step-number w-16 h-16 rounded-xl flex items-center justify-center text-2xl font-bold text-white mb-4">
                        1
                    </div>
                    <div class="mb-4">
                        <i class="fas fa-user-plus text-blue-400 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Create Account</h3>
                    <p class="text-gray-400 text-sm mb-4 flex-grow leading-relaxed">
                        Register a free account on our website. Quick and secure registration process.
                    </p>
                    <a href="#" class="inline-flex items-center px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 mt-auto">
                        Register Now
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="step-card bg-gray-900 rounded-2xl p-6 border border-gray-700">
                <div class="flex flex-col items-center text-center h-full">
                    <div class="step-number w-16 h-16 rounded-xl flex items-center justify-center text-2xl font-bold text-white mb-4">
                        2
                    </div>
                    <div class="mb-4">
                        <i class="fas fa-download text-green-400 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Download Client</h3>
                    <p class="text-gray-400 text-sm mb-4 flex-grow leading-relaxed">
                        Download the WoW client compatible with our server. Fast mirrors worldwide.
                    </p>
                    <div class="flex flex-col gap-2 w-full mt-auto">
                        <button class="download-button inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 relative z-10">
                            <i class="fas fa-download mr-2"></i>
                            Windows
                        </button>
                        <button class="download-button inline-flex items-center justify-center px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 relative z-10">
                            <i class="fab fa-apple mr-2"></i>
                            macOS
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="step-card bg-gray-900 rounded-2xl p-6 border border-gray-700">
                <div class="flex flex-col items-center text-center h-full">
                    <div class="step-number w-16 h-16 rounded-xl flex items-center justify-center text-2xl font-bold text-white mb-4">
                        3
                    </div>
                    <div class="mb-4">
                        <i class="fas fa-cog text-purple-400 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Configure Realmlist</h3>
                    <p class="text-gray-400 text-sm mb-4 flex-grow leading-relaxed">
                        Modify the realmlist.wtf file to connect to our server.
                    </p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="step-card bg-gray-900 rounded-2xl p-6 border border-gray-700">
                <div class="flex flex-col items-center text-center h-full">
                    <div class="step-number pulse-dot w-16 h-16 rounded-xl flex items-center justify-center text-2xl font-bold text-white mb-4">
                        4
                    </div>
                    <div class="mb-4">
                        <i class="fas fa-play-circle text-yellow-400 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Launch & Play</h3>
                    <p class="text-gray-400 text-sm mb-4 flex-grow leading-relaxed">
                        Start the game, login, select realm and create your character!
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Troubleshooting & FAQ -->
<section id="faq" class="py-20 bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">Frequently Asked Questions</h2>
            <p class="text-gray-400 text-lg">Find answers to common questions</p>
        </div>

        <div class="max-w-4xl mx-auto space-y-4" x-data="{ open: null }">
            <!-- FAQ Item 1 -->
            <div class="faq-item bg-gray-800 rounded-xl overflow-hidden border border-gray-700">
                <button @click="open = open === 1 ? null : 1" class="w-full px-8 py-6 text-left flex items-center justify-between hover:bg-gray-750 transition-colors duration-300">
                    <span class="font-semibold text-lg">Is it really free to play?</span>
                    <i class="fas transition-transform duration-300" :class="open === 1 ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
                <div x-show="open === 1" x-transition class="px-8 pb-6 text-gray-400">
                    Yes! {{ config('app.name', 'NexusCMS') }} is completely free to play. We don't charge for access to the game, and all content is available to all players. Optional donations support server costs but provide no gameplay advantages.
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="faq-item bg-gray-800 rounded-xl overflow-hidden border border-gray-700">
                <button @click="open = open === 2 ? null : 2" class="w-full px-8 py-6 text-left flex items-center justify-between hover:bg-gray-750 transition-colors duration-300">
                    <span class="font-semibold text-lg">What version of WoW do you support?</span>
                    <i class="fas transition-transform duration-300" :class="open === 2 ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
                <div x-show="open === 2" x-transition class="px-8 pb-6 text-gray-400">
                    We currently support World of Warcraft: Wrath of the Lich King (3.3.5a). This is one of the most popular and stable versions with excellent custom content support.
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="faq-item bg-gray-800 rounded-xl overflow-hidden border border-gray-700">
                <button @click="open = open === 3 ? null : 3" class="w-full px-8 py-6 text-left flex items-center justify-between hover:bg-gray-750 transition-colors duration-300">
                    <span class="font-semibold text-lg">Can I transfer my characters from retail?</span>
                    <i class="fas transition-transform duration-300" :class="open === 3 ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
                <div x-show="open === 3" x-transition class="px-8 pb-6 text-gray-400">
                    No, characters from retail World of Warcraft cannot be transferred to private servers. You'll need to create new characters and experience the journey fresh. Many players enjoy starting anew!
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="faq-item bg-gray-800 rounded-xl overflow-hidden border border-gray-700">
                <button @click="open = open === 4 ? null : 4" class="w-full px-8 py-6 text-left flex items-center justify-between hover:bg-gray-750 transition-colors duration-300">
                    <span class="font-semibold text-lg">I can't connect to the server. What should I do?</span>
                    <i class="fas transition-transform duration-300" :class="open === 4 ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
                <div x-show="open === 4" x-transition class="px-8 pb-6 text-gray-400">
                    First, double-check your realmlist.wtf file is correctly configured. Make sure your firewall isn't blocking the connection. If issues persist, check our Discord for server status updates or contact support.
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="faq-item bg-gray-800 rounded-xl overflow-hidden border border-gray-700">
                <button @click="open = open === 5 ? null : 5" class="w-full px-8 py-6 text-left flex items-center justify-between hover:bg-gray-750 transition-colors duration-300">
                    <span class="font-semibold text-lg">What are the server rates?</span>
                    <i class="fas transition-transform duration-300" :class="open === 5 ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
                <div x-show="open === 5" x-transition class="px-8 pb-6 text-gray-400">
                    Our server features 5x experience rates, 3x reputation rates, and 2x drop rates. These rates provide a balanced experience that respects your time while maintaining the essence of classic gameplay.
                </div>
            </div>

            <!-- FAQ Item 6 -->
            <div class="faq-item bg-gray-800 rounded-xl overflow-hidden border border-gray-700">
                <button @click="open = open === 6 ? null : 6" class="w-full px-8 py-6 text-left flex items-center justify-between hover:bg-gray-750 transition-colors duration-300">
                    <span class="font-semibold text-lg">How do I report bugs or issues?</span>
                    <i class="fas transition-transform duration-300" :class="open === 6 ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
                <div x-show="open === 6" x-transition class="px-8 pb-6 text-gray-400">
                    You can report bugs or issues on our Discord server in the #bug-reports channel. Include as much detail as possible, such as steps to reproduce, screenshots, and any error messages.
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function copyRealmlist() {
        const text = 'set realmlist logon.nexuscms.com';
        navigator.clipboard.writeText(text).then(function() {
            // Show a temporary success message
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check mr-1"></i>Copied!';
            button.classList.add('text-green-400');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('text-green-400');
            }, 2000);
        });
    }
</script>
@endsection