<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Successful - NexusCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-lg text-center">
        <div class="mb-6">
            <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7"/>
            </svg>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Installation Successful!</h1>
            <p class="text-gray-600">Your NexusCMS installation is complete. You can now log in with your administrator account.</p>
        </div>
        <a href="{{ url('/') }}" 
           class="mt-4 inline-block px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors duration-200">
           Go to Homepage
        </a>
        <a href="{{ route('login') }}" 
           class="mt-4 inline-block px-6 py-3 text-indigo-600 border border-indigo-600 font-medium rounded-lg hover:bg-indigo-50 transition-colors duration-200 ml-2">
           Log In
        </a>
    </div>
</body>
</html>
