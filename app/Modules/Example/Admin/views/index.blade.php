@extends('admin::layouts.app')

@section('title', 'Example Module')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <h1 class="text-2xl font-bold text-gray-800">Example Module — Admin</h1>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm">
            This is the custom admin view for the <strong>Example</strong> module (Modo B — Custom Views).
        </p>
    </div>
</main>
@endsection
