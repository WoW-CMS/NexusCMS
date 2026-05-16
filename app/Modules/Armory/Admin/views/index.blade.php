@extends('admin::layouts.app')

@section('title', 'Armory Settings')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <h1 class="text-2xl font-bold text-gray-800">Armory</h1>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    <div class="bg-white rounded-lg shadow p-6 max-w-xl">
        <p class="text-gray-600 text-sm mb-4">
            The Armory module reads character data directly from the game database via the
            <code class="text-xs bg-gray-100 px-1 py-0.5 rounded">ArmoryRepositoryInterface</code>.
            No CMS-side records are stored.
        </p>
        <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-lg px-4 py-3 text-sm">
            <i class="fas fa-info-circle mr-1"></i>
            To configure the game database connection, edit <code>config/database.php</code>
            and set the <code>characters</code> / <code>world</code> connection credentials.
        </div>
    </div>
</main>
@endsection
