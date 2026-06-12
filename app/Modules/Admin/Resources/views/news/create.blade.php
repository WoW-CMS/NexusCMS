@extends('admin::layouts.app')

@section('title', 'Create News Article')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.news.index') }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Create News Article</h1>
    </div>
    <div class="flex items-center gap-3">
        <button type="submit" form="news-form" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
            <i class="fas fa-save mr-1"></i> Publish
        </button>
    </div>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-sm font-semibold text-red-700 mb-1">Please fix the following errors:</p>
            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="news-form" method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main content --}}
            <div class="lg:col-span-2 space-y-6">

                @if($isMultilingual && count($activeLocales) > 1)
                {{-- Multilingual tabs --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="border-b border-gray-200 px-4">
                        <nav class="-mb-px flex gap-1" id="lang-tabs" role="tablist">
                            @foreach($activeLocales as $i => $locale)
                            <button type="button"
                                    role="tab"
                                    data-locale="{{ $locale }}"
                                    onclick="switchTab('{{ $locale }}')"
                                    class="lang-tab px-4 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap
                                           {{ $i === 0 ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="text-xs font-mono px-1.5 py-0.5 rounded bg-gray-100">{{ strtoupper($locale) }}</span>
                                    {{ $localeNames[$locale] ?? $locale }}
                                    @if($locale === $defaultLocale)
                                        <span class="text-[10px] text-blue-400 font-normal">(default)</span>
                                    @endif
                                </span>
                            </button>
                            @endforeach
                        </nav>
                    </div>

                    @foreach($activeLocales as $i => $locale)
                    <div id="tab-{{ $locale }}" class="lang-panel p-6 space-y-4 {{ $i !== 0 ? 'hidden' : '' }}">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Title <span class="font-mono text-xs text-gray-400">[{{ $locale }}]</span>
                                @if($locale === $defaultLocale) <span class="text-red-500">*</span> @endif
                            </label>
                            <input type="text"
                                   name="title_translations[{{ $locale }}]"
                                   value="{{ old("title_translations.{$locale}") }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   {{ $locale === $defaultLocale ? 'required' : '' }}
                                   id="title-{{ $locale }}"
                                   placeholder="Article title in {{ $localeNames[$locale] ?? $locale }}">
                            @if($locale === $defaultLocale)
                                <p class="text-xs text-gray-400 mt-1">Slug will be auto-generated from this title if left blank.</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Excerpt <span class="font-mono text-xs text-gray-400">[{{ $locale }}]</span>
                            </label>
                            <p class="text-xs text-gray-500 mb-2">Short summary shown in news listings. Optional.</p>
                            <textarea name="excerpt_translations[{{ $locale }}]"
                                id="ck-excerpt-{{ $locale }}"
                                rows="4"
                                class="news-ck-excerpt w-full">{{ old("excerpt_translations.{$locale}") }}
                            </textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Content <span class="font-mono text-xs text-gray-400">[{{ $locale }}]</span>
                                @if($locale === $defaultLocale) <span class="text-red-500">*</span> @endif
                            </label>
                            <textarea name="content_translations[{{ $locale }}]"
                                      id="ck-content-{{ $locale }}"
                                      rows="25"
                                      data-required="{{ $locale === $defaultLocale ? 'true' : 'false' }}"
                                      class="news-ck-editor w-full">{{ old("content_translations.{$locale}") }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                {{-- Single language mode --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-800">Content</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                            <input type="text"
                                   name="title"
                                   value="{{ old('title') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   id="title-main"
                                   placeholder="Article title">
                            <p class="text-xs text-gray-400 mt-1">Slug will be auto-generated if left blank.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Excerpt</label>
                            <p class="text-xs text-gray-500 mb-2">Short summary shown in news listings. Optional.</p>
                            <textarea name="excerpt"
                                      id="ck-excerpt-main"
                                      rows="4"
                                      class="news-ck-excerpt w-full">{{ old('excerpt') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Content <span class="text-red-500">*</span></label>
                            <textarea name="content"
                                      id="ck-content-main"
                                      rows="25"
                                      data-required="true"
                                      class="news-ck-editor w-full">{{ old('content') }}</textarea>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Publish --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-base font-bold text-gray-800">Publish</h3>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" id="is_published" name="is_published" value="1"
                                   {{ old('is_published') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_published" class="text-sm font-medium text-gray-700">Publish immediately</label>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Publish date</label>
                            <input type="datetime-local" name="published_at"
                                   value="{{ old('published_at') }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="p-4 border-t border-gray-100">
                        <button type="submit" form="news-form" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                            <i class="fas fa-save mr-1"></i> Save Article
                        </button>
                    </div>
                </div>

                {{-- Slug --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-base font-bold text-gray-800">Permalink</h3>
                    </div>
                    <div class="p-4">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Slug</label>
                        <input type="text" name="slug" id="slug-input"
                               value="{{ old('slug') }}"
                               placeholder="auto-generated-from-title"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono">
                        <p class="text-xs text-gray-400 mt-1">/news/<span id="slug-preview" class="font-medium">your-slug</span></p>
                    </div>
                </div>

                {{-- Category --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-base font-bold text-gray-800">Category</h3>
                    </div>
                    <div class="p-4">
                        <select name="category_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">— No category —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-base font-bold text-gray-800">Featured Image</h3>
                    </div>
                    <div class="p-4">
                        <div id="image-preview" class="hidden mb-3">
                            <img id="image-preview-img" src="" alt="" class="w-full rounded-lg object-cover max-h-40">
                        </div>
                        <label class="block w-full cursor-pointer border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition">
                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-1 block"></i>
                            <span class="text-sm text-gray-500">Click to upload image</span>
                            <input type="file" name="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                        </label>
                        <p class="text-xs text-gray-400 mt-1">Recommended: 1200×630px, max 4MB</p>
                    </div>
                </div>

            </div>
        </div>
    </form>
</main>

@push('styles')
<style>
    .ck-editor__editable { min-height: 500px !important; }
    .ck-excerpt .ck-editor__editable { min-height: 130px !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

<script>
    // ── CKEditor 5 instances ──────────────────────────────────────────────
    const editors = {};

    const ck5Config = {
        licenseKey: 'GPL',
        toolbar: [
            'undo', 'redo',
            '|',
            'bold', 'italic', 'underline', 'strikethrough',
            '|',
            'numberedList', 'bulletedList',
            '|',
            'outdent', 'indent',
            '|',
            'blockQuote',
            '|',
            'link', 'insertTable', 'imageUpload',
            '|',
            'heading'
        ],
        image: {
            toolbar: ['imageTextAlternative', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side']
        },
        table: {
            contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
        }
    };

    const ck5ExcerptConfig = {
        licenseKey: 'GPL',
        toolbar: ['bold', 'italic', '|', 'link', 'bulletedList', 'numberedList'],
    };

    document.querySelectorAll('.news-ck-editor').forEach(function (textarea) {
        ClassicEditor
            .create(textarea, ck5Config)
            .then(editor => {
                editors[textarea.name] = editor;
            })
            .catch(error => {
                console.error(error);
            });
    });

    document.querySelectorAll('.news-ck-excerpt').forEach(function (textarea) {
        ClassicEditor
            .create(textarea, ck5ExcerptConfig)
            .then(editor => {
                editors[textarea.name] = editor;
                editor.ui.view.editable.element.closest('.ck-editor').classList.add('ck-excerpt');
            })
            .catch(error => {
                console.error(error);
            });
    });

    // ── Sync data + validate required fields before submit ───────────────
    document.getElementById('news-form').addEventListener('submit', function (e) {
        let valid = true;
        for (const name in editors) {
            const editor = editors[name];
            const textarea = document.querySelector(`[name="${CSS.escape(name)}"]`);
            if (textarea) {
                const data = editor.getData();
                textarea.value = data;
                if (textarea.dataset.required === 'true' && !data.trim()) {
                    valid = false;
                    editor.ui.view.editable.element.style.border = '2px solid #ef4444';
                    editor.ui.view.editable.element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    if (editor.ui.view.editable.element) {
                        editor.ui.view.editable.element.style.border = '';
                    }
                }
            }
        }
        if (!valid) e.preventDefault();
    });

    // ── Tab switching ─────────────────────────────────────────────────────
    function switchTab(locale) {
        document.querySelectorAll('.lang-panel').forEach(p => p.classList.add('hidden'));
        document.querySelectorAll('.lang-tab').forEach(t => {
            t.classList.remove('border-blue-500', 'text-blue-600');
            t.classList.add('border-transparent', 'text-gray-500');
        });
        document.getElementById('tab-' + locale)?.classList.remove('hidden');
        document.querySelector('[data-locale="' + locale + '"]')?.classList.add('border-blue-500', 'text-blue-600');
        document.querySelector('[data-locale="' + locale + '"]')?.classList.remove('border-transparent', 'text-gray-500');
    }

    // ── Slug generation ───────────────────────────────────────────────────
    const defaultTitleInput = document.getElementById('title-{{ $defaultLocale }}') || document.getElementById('title-main');
    const slugInput = document.getElementById('slug-input');
    const slugPreview = document.getElementById('slug-preview');

    function slugify(text) {
        return text.toString().toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/\s+/g, '-').replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-').replace(/^-+/, '').replace(/-+$/, '');
    }

    if (defaultTitleInput) {
        defaultTitleInput.addEventListener('input', function () {
            if (!slugInput.value) {
                slugPreview.textContent = slugify(this.value) || 'your-slug';
            }
        });
    }

    slugInput.addEventListener('input', function () {
        slugPreview.textContent = slugify(this.value) || 'your-slug';
    });

    // ── Image preview ─────────────────────────────────────────────────────
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const img = document.getElementById('image-preview-img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                img.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection