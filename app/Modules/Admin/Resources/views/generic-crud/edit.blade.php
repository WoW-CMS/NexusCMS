@extends('admin::layouts.app')

@section('title', 'Edit — ' . ($config['title'] ?? 'Record'))

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit {{ $config['title'] ?? 'Record' }}</h1>
    <a href="{{ route($base . 'index') }}"
       class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm font-medium">
        <i class="fas fa-arrow-left mr-2"></i>Back
    </a>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 border border-green-200 rounded-lg flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
            <p class="text-red-700 text-sm font-medium mb-2">Please fix the following errors:</p>
            <ul class="text-sm text-red-700 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route($base . 'update', $record->getKey()) }}"
          enctype="multipart/form-data">
        @csrf @method('PUT')

        @include('admin::generic-crud._form', ['config' => $config, 'record' => $record])

        <div class="mt-6 flex items-center gap-3">
            <button type="submit"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                <i class="fas fa-save mr-2"></i>Save Changes
            </button>
            <a href="{{ route($base . 'index') }}"
               class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium">
                Cancel
            </a>
        </div>
    </form>
</main>
@endsection
