@extends('admin::layouts.app')

@section('title', 'Edit News Article')

@section('content')
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.news.index') }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Article</h1>
        <span class="text-sm text-gray-400 font-mono">/news/{{ $news->slug }}</span>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('news.show', $news->slug) }}" target="_blank" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm font-medium">
            <i class="fas fa-eye mr-1"></i> View
        </a>
        <button type="submit" form="news-form" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
            <i class="fas fa-save mr-1"></i> Save Changes
        </button>
    </div>
</header>

<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 border border-green-200 rounded-lg flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
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

    <form id="news-form" method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main content --}}
            <div class="lg:col-span-2 space-y-6">

                @if($isMultilingual && count($activeLocales) > 1)
                {{-- Excerpt (non-translated) --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-base font-bold text-gray-800">Excerpt</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Short summary shown in news listings. Optional.</p>
                    </div>
                    <div class="p-6">
                        <textarea name="excerpt"
                                  id="ck-excerpt-main"
                                  rows="4"
                                  class="news-ck-excerpt w-full">{{ old('excerpt', $news->excerpt) }}</textarea>
                    </div>
                </div>

                {{-- Multilingual tabs --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="border-b border-gray-200 px-4">
                        <nav class="-mb-px flex gap-1" id="lang-tabs">
                            @foreach($activeLocales as $i => $locale)
                            <button type="button"
                                    data-locale="{{ $locale }}"
                                    onclick="switchTab('{{ $locale }}')"
                                    class="lang-tab px-4 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap
                                           {{ $i === 0 ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="text-xs font-mono px-1.5 py-0.5 rounded bg-gray-100">{{ strtoupper($locale) }}</span>
                                    {{ $localeNames[$locale] ?? $locale }}
                                    @if($locale === $defaultLocale)
                                        <span class="text-[10px] text-blue-400">(default)</span>
                                    @endif
                                    @php
                                        $hasTranslation = !empty($news->title_translations[$locale]);
                                    @endphp
                                    @if($hasTranslation)
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block" title="Has translation"></span>
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
                                   value="{{ old("title_translations.{$locale}", $news->title_translations[$locale] ?? '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   {{ $locale === $defaultLocale ? 'required' : '' }}
                                   placeholder="Article title in {{ $localeNames[$locale] ?? $locale }}">
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
                                      class="news-ck-editor w-full">{{ old("content_translations.{$locale}", $news->content_translations[$locale] ?? '') }}</textarea>
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
                                   value="{{ old('title', $news->title) }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Excerpt</label>
                            <p class="text-xs text-gray-500 mb-2">Short summary shown in news listings. Optional.</p>
                            <textarea name="excerpt"
                                      id="ck-excerpt-main"
                                      rows="4"
                                      class="news-ck-excerpt w-full">{{ old('excerpt', $news->excerpt) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Content <span class="text-red-500">*</span></label>
                            <textarea name="content"
                                      id="ck-content-main"
                                      rows="25"
                                      data-required="true"
                                      class="news-ck-editor w-full">{{ old('content', $news->content) }}</textarea>
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
                                   {{ old('is_published', $news->is_published) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_published" class="text-sm font-medium text-gray-700">Published</label>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Publish date</label>
                            <input type="datetime-local" name="published_at"
                                   value="{{ old('published_at', $news->published_at?->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="text-xs text-gray-400">
                            Created: {{ $news->created_at->format('M d, Y H:i') }}<br>
                            Updated: {{ $news->updated_at->format('M d, Y H:i') }}
                        </div>
                    </div>
                    <div class="p-4 border-t border-gray-100">
                        <button type="submit" form="news-form" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                            <i class="fas fa-save mr-1"></i> Save Changes
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
                               value="{{ old('slug', $news->slug) }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono">
                        <p class="text-xs text-gray-400 mt-1">/news/<span id="slug-preview">{{ $news->slug }}</span></p>
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
                                <option value="{{ $cat->id }}" {{ old('category_id', $news->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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
                        @if($news->image)
                        <div class="mb-3">
                            <img src="{{ asset('storage/images/' . $news->image) }}" alt="" class="w-full rounded-lg object-cover max-h-40">
                            <p class="text-xs text-gray-400 mt-1">Current image. Upload a new one to replace.</p>
                        </div>
                        @endif
                        <div id="image-preview" class="hidden mb-3">
                            <img id="image-preview-img" src="" alt="" class="w-full rounded-lg object-cover max-h-40">
                        </div>
                        <label class="block w-full cursor-pointer border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition">
                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-1 block"></i>
                            <span class="text-sm text-gray-500">{{ $news->image ? 'Replace image' : 'Upload image' }}</span>
                            <input type="file" name="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                        </label>
                    </div>
                </div>

                {{-- Danger zone --}}
                <div class="bg-white rounded-lg shadow border border-red-100">
                    <div class="p-4 border-b border-red-100">
                        <h3 class="text-base font-bold text-red-700">Danger Zone</h3>
                    </div>
                    <div class="p-4">
                        <button type="submit" form="delete-news-form"
                                onclick="return confirm('Are you sure you want to delete this article?')"
                                class="w-full px-4 py-2 bg-red-50 text-red-700 border border-red-200 rounded-lg hover:bg-red-100 text-sm font-medium transition">
                            <i class="fas fa-trash mr-1"></i> Delete Article
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>

    {{-- Delete form is intentionally OUTSIDE the main form to prevent _method conflicts --}}
    <form id="delete-news-form" method="POST" action="{{ route('admin.news.destroy', $news) }}">
        @csrf
        @method('DELETE')
    </form>
</main>

@push('scripts')
<style>
    .ck-editor__editable { min-height: 500px !important; }
    .ck-excerpt .ck-editor__editable { min-height: 130px !important; }
</style>
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