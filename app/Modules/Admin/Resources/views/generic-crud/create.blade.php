@extends('admin::layouts.app')

@section('title', 'New ' . ($config['title'] ?? 'Record'))

@section('content')

{{-- Page header --}}
<div class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between gap-4 shrink-0">
    <div class="flex items-center gap-3">
        <a href="{{ route($base . 'index') }}"
           class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-gray-400
                  hover:text-gray-600 hover:bg-gray-100 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-0.5">
                <a href="{{ route($base . 'index') }}" class="hover:text-gray-600 transition">{{ $config['title'] ?? 'Records' }}</a>
                <i class="fas fa-chevron-right text-[9px]"></i>
                <span class="text-gray-500">New</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900">
                New {{ \Illuminate\Support\Str::singular($config['title'] ?? 'Record') }}
            </h1>
        </div>
    </div>
</div>

<div class="flex-1 overflow-y-auto bg-gray-50">
    <form method="POST" action="{{ route($base . 'store') }}" enctype="multipart/form-data" id="crud-form">
        @csrf

        <div class="p-6 pb-28">
            {{-- Validation errors --}}
            @if($errors->any())
                <div class="mb-6 flex gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-sm">
                    <i class="fas fa-circle-exclamation text-red-500 mt-0.5 shrink-0"></i>
                    <div>
                        <p class="font-semibold text-red-800 mb-1">Please fix the following errors:</p>
                        <ul class="text-red-700 space-y-0.5 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @include('admin::generic-crud._form', ['config' => $config, 'record' => null])
        </div>

        {{-- Sticky save bar --}}
        <div class="fixed bottom-0 right-0 left-0 md:left-64 bg-white border-t border-gray-200 px-6 py-3
                    flex items-center justify-between gap-4 z-10 shadow-sm">
            <p class="text-xs text-gray-400">
                <i class="fas fa-circle-info mr-1"></i>
                Fields marked <span class="text-red-500 font-semibold">*</span> are required.
            </p>
            <div class="flex items-center gap-2">
                <a href="{{ route($base . 'index') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 text-white text-sm font-medium
                               rounded-lg hover:bg-blue-700 active:bg-blue-800 transition shadow-sm">
                    <i class="fas fa-save text-xs"></i>
                    Save
                </button>
            </div>
        </div>

    </form>
</div>

@endsection
