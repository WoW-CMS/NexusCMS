@extends('layouts.main')

@section('content')
    <!-- Hero Section sonbaba61 -->
    <div class="relative">
        <div class="bg-white">
            <div class="max-w-7xl mx-auto pt-32 px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center text-center">
                <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 mb-4">
                    Instagram Araçları <span class="text-blue-600">Takip DEV</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-700 max-w-3xl mb-8">
                    Instagram organik büyüme ve etkileşim arttırma platformu, hesabını organik büyütmek için aramıza katıl.
                </p>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:text-lg">
                        Hesap Oluştur?
                    </a>
                    <a href="#" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 md:text-lg">
                        Hizmet Detayları
                    </a>
                </div>
            </div>
        </div>
    </div>
<!-- Two Column Layout: News and Realm Status -->
<div class="py-12 bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- News Column (Larger) -->
            <div class="lg:w-2/3">
                <div class="text-center lg:text-left mb-8">
                    <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                        Latest News
                    </h2>
                    <p class="mt-3 max-w-2xl mx-auto lg:mx-0 text-xl text-gray-400 sm:mt-4">
                        Stay up to date with the latest server news, events, and updates.
                    </p>
                </div>

                <div class="space-y-8">
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg news-card flex flex-col md:flex-row">
                        <div class="md:w-1/3">
                            <img class="w-full h-48 md:h-full object-cover" 
                                 src="" 
                                 alt="">
                        </div>
                        <div class="md:w-2/3 p-6">
                            <div class="text-xs text-gray-400 mb-2">423432</div>
                            <h3 class="text-xl font-bold text-white mb-2">fdsfdsfds</h3>
                            <p class="text-gray-300 mb-4">fdsfdsfdsfsd</p>
                            <a href="" class="text-blue-400 hover:text-blue-300 font-medium">Read More →</a>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-8">
                    <a href="" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        View All News
                    </a>
                </div>
            </div>

            <!-- Realm Status Column (Smaller) -->
            <div class="lg:w-1/3">
                <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
                    <h3 class="text-xl font-bold text-white mb-4">Realm Status</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300">Azeroth</span>
                            <span class="text-green-400 font-semibold">Online</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300">Outland</span>
                            <span class="text-yellow-400 font-semibold">Maintenance</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300">Northrend</span>
                            <span class="text-red-500 font-semibold">Offline</span>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- flex -->
    </div> <!-- container -->
</div> <!-- section -->

    <!-- Features Section -->
    <div class="py-6 pt-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Server Features
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-600 sm:mt-4">
                    Discover what makes our server unique and exciting.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-6 rounded-lg shadow-lg feature-card border border-gray-100">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mb-3">
                            <i class="fas fa-code text-2xl feature-icon"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Custom Content</h3>
                    </div>
                    <p class="text-gray-700 text-center">Experience unique dungeons, raids, and quests found nowhere else.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white p-6 rounded-lg shadow-lg feature-card border border-gray-100">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mb-3">
                            <i class="fas fa-balance-scale text-2xl feature-icon"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Balanced Gameplay</h3>
                    </div>
                    <p class="text-gray-700 text-center">Carefully tuned classes and encounters for the best gaming experience.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white p-6 rounded-lg shadow-lg feature-card border border-gray-100">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mb-3">
                            <i class="fas fa-users text-2xl feature-icon"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Active Community</h3>
                    </div>
                    <p class="text-gray-700 text-center">Join thousands of players in a friendly and helpful community.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-white p-6 rounded-lg shadow-lg feature-card border border-gray-100">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mb-3">
                            <i class="fas fa-server text-2xl feature-icon"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Stable Servers</h3>
                    </div>
                    <p class="text-gray-700 text-center">Enjoy high uptime and low latency on our professionally maintained servers.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How to Join Section -->
    <div class="py-12 bg-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    How to Join
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-600 sm:mt-4">
                    Follow these simple steps to start your adventure.
                </p>
            </div>
            <div class="flex flex-col md:flex-row justify-center items-center md:items-start space-y-8 md:space-y-0 md:space-x-12">
                <!-- Step 1 -->
                <div class="flex flex-col items-center max-w-xs text-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-500 text-white text-2xl font-bold mb-4">1</div>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Create an Account</h3>
                    <p class="text-gray-600">Register for free on our website to create your game account.</p>
                </div>
                
                <!-- Step 2 -->
                <div class="flex flex-col items-center max-w-xs text-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-500 text-white text-2xl font-bold mb-4">2</div>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Download the Client</h3>
                    <p class="text-gray-600">Download our custom WoW client or use your existing installation.</p>
                </div>
                
                <!-- Step 3 -->
                <div class="flex flex-col items-center max-w-xs text-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-500 text-white text-2xl font-bold mb-4">3</div>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Configure & Connect</h3>
                    <p class="text-gray-600">Set up your realmlist.wtf file with our server address and log in.</p>
                </div>
            </div>
            <div class="text-center mt-12">
                <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Detailed Connection Guide
                </a>
            </div>
        </div>
    </div>

    <!-- Discord Section -->
    <div class="py-12 bg-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="mb-8 md:mb-0 md:mr-8">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Join Our Discord Community</h2>
                    <p class="text-xl text-blue-700 mb-6">Connect with other players, get support, and stay updated on server news.</p>
                    <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <i class="fab fa-discord mr-2"></i> Join Discord
                    </a>
                </div>
                <div class="flex-shrink-0">
                    <img src="https://assets-global.website-files.com/6257adef93867e50d84d30e2/636e0a6a49cf127bf92de1e2_icon_clyde_blurple_RGB.png" alt="Discord" class="h-32">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection