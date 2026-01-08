@extends('layouts.main')

@section('content')
<section class="min-h-screen flex items-center justify-center pt-16 relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl float"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl float" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-red-500/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 w-full max-w-6xl mx-4 my-8">
        <div class="grid lg:grid-cols-2 gap-0 bg-slate-900/50 backdrop-blur-xl rounded-3xl overflow-hidden border border-slate-800/50 shadow-2xl">
            <!-- Left Side - Hero Image & Info -->
            <div class="relative hidden lg:block hero-image">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 via-purple-900/90 to-slate-900/90"></div>
                <div class="relative h-full flex flex-col justify-between p-12">
                    <div>
                        <h1 class="text-4xl font-bold text-white mb-4">Welcome Back to NexusCMS</h1>
                        <p class="text-slate-300 text-lg leading-relaxed">
                            Join thousands of players in the ultimate World of Warcraft experience. Your adventure awaits!
                        </p>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0 border border-blue-500/30">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white mb-1">Season 3 Live Now</h3>
                                <p class="text-slate-300 text-sm">New raids, dungeons, and exclusive rewards</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0 border border-purple-500/30">
                                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white mb-1">Active Community</h3>
                                <p class="text-slate-300 text-sm">Join 10,000+ players online right now</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center flex-shrink-0 border border-green-500/30">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white mb-1">99.9% Uptime</h3>
                                <p class="text-slate-300 text-sm">Stable servers with professional support</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="p-8 sm:p-12 flex flex-col justify-center">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-8 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-red-800 rounded-xl flex items-center justify-center text-3xl font-bold shadow-lg shadow-red-900/50 mx-auto mb-4">
                        ?
                    </div>
                    <h2 class="text-2xl font-bold text-white">Welcome Back</h2>
                </div>

                <div class="mb-8 hidden lg:block">
                    <h2 class="text-3xl font-bold text-white mb-2">Login to Your Account</h2>
                    <p class="text-slate-400">Enter your credentials to continue</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">                
                    @csrf
                    <!-- Email Input -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input 
                                type="email"
                                name="email" 
                                id="email" 
                                value="{{ old('email') }}"
                                class="w-full pl-12 pr-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                placeholder="Enter your email"
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input 
                                type="password"
                                name="password" 
                                id="password" 
                                value="{{ old('password') }}"
                                class="w-full pl-12 pr-12 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                placeholder="Enter your password"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-slate-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="w-4 h-4 rounded border-slate-700 bg-slate-800 text-blue-600 focus:ring-2 focus:ring-blue-500/20 focus:ring-offset-0 transition-all">
                            <span class="ml-2 text-sm text-slate-300 group-hover:text-white transition-colors">Remember me</span>
                        </label>
                        <a href="#" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">Forgot password?</a>
                    </div>

                    <!-- Sign In Button -->
                    <button 
                        type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-xl transition-all duration-200 shadow-lg shadow-blue-900/30 hover:shadow-blue-900/50 transform hover:scale-[1.02]"
                    >
                        Sign in
                    </button>
                    
                    <!-- Register Link -->
                    <div class="text-center pt-4">
                        <p class="text-slate-400">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 font-medium transition-colors">Register here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
