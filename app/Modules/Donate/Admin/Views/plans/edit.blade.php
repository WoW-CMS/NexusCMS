@extends('admin::layouts.app')

@section('title', 'Edit Donation Plan')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit Plan — {{ $plan->name }}</h1>
    <a href="{{ route('admin.donate.plans.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.donate.plans.update', $plan) }}">
            @csrf @method('PUT')
            @include('donate-admin::plans._form', ['plan' => $plan])
            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</main>
@endsection
