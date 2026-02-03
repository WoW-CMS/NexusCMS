<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Maintenance Mode' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
            background-image: url('https://images.unsplash.com/photo-1519074069444-1ba4fff66d16?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            background-blend-mode: overlay;
        }
        .cinzel {
            font-family: 'Cinzel', serif;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-gray-900/90 backdrop-blur-md p-8 rounded-2xl shadow-2xl border border-gray-700 text-center">
        <div class="mb-6">
            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white cinzel mb-2">Maintenance Mode</h1>
            <div class="h-1 w-20 bg-blue-600 mx-auto rounded-full"></div>
        </div>
        
        <p class="text-gray-300 mb-8 leading-relaxed">
            {{ $message }}
        </p>

        <!-- Links -->
         <div class="mt-8 mb-6">
            @can('access.admin.panel')
                <a href="{{ route('admin.index') }}" class="inline-block px-6 py-3 bg-yellow-600 text-white rounded-full font-semibold hover:bg-yellow-700 transition duration-300 ml-4">
                    Admin Panel
                </a>
            @endcan

            @if(auth()->check())
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="inline-block px-6 py-3 bg-green-600 text-white rounded-full font-semibold hover:bg-green-700 transition duration-300 ml-4">
                    Logout
                </button>
            </form>
            @else
                <a href="{{ route('login') }}" class="inline-block px-6 py-3 bg-green-600 text-white rounded-full font-semibold hover:bg-green-700 transition duration-300 ml-4">
                    Login
                </a>
            @endif
        </div>
        
        <div class="text-sm text-gray-500">
            &copy; {{ date('Y') }} {{ settings('site_name', 'NexusCMS') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
