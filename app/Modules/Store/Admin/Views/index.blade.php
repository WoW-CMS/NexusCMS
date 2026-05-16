@extends('admin::layouts.app')

@section('title', 'Store Overview')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <h1 class="text-2xl font-bold text-gray-800">Store — Products</h1>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg px-4 py-3 text-sm">
        <i class="fas fa-info-circle mr-1"></i>
        Products are currently defined in code (<code>StoreController::getProducts()</code>).
        Edit the source file to add, remove or change costs.
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Key</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Cost (DP)</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Needs Character</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($products as $key => $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-mono text-xs text-gray-500">{{ $key }}</td>
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $product['name'] }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $product['description'] }}</td>
                        <td class="px-6 py-3 text-gray-700 font-semibold">{{ number_format($product['cost']) }}</td>
                        <td class="px-6 py-3">
                            @if($product['requires_character'] ?? false)
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Yes</span>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($realms->isNotEmpty())
        <h2 class="mt-8 text-lg font-semibold text-gray-800 mb-3">Configured Realms</h2>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Realm</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Host</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Console Port</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($realms as $realm)
                        <tr>
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $realm->name }}</td>
                            <td class="px-6 py-3 font-mono text-xs text-gray-500">{{ $realm->console_hostname }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $realm->console_port }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</main>
@endsection
