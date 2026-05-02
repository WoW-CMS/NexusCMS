<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Services\LocalizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminNewsController extends Controller
{
    public function index()
    {
        $news = News::withTrashed()
            ->with(['author', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin::news.index', compact('news'));
    }

    public function create()
    {
        $categories      = NewsCategory::where('is_active', true)->orderBy('name')->get();
        $activeLocales   = LocalizationService::getActiveLocales();
        $defaultLocale   = LocalizationService::getDefaultLocale();
        $localeNames     = LocalizationService::getLocaleNames();
        $isMultilingual  = LocalizationService::isMultilingualEnabled();

        return view('admin::news.create', compact(
            'categories', 'activeLocales', 'defaultLocale', 'localeNames', 'isMultilingual'
        ));
    }

    public function store(Request $request)
    {
        $activeLocales  = LocalizationService::getActiveLocales();
        $defaultLocale  = LocalizationService::getDefaultLocale();
        $isMultilingual = LocalizationService::isMultilingualEnabled();

        $rules = [
            'category_id'  => ['nullable', 'exists:news_category,id'],
            'image'        => ['nullable', 'image', 'max:4096'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'slug'         => ['nullable', 'string', 'max:255', 'unique:news,slug'],
        ];

        if ($isMultilingual) {
            foreach ($activeLocales as $locale) {
                $required = $locale === $defaultLocale ? 'required' : 'nullable';
                $rules["title_translations.{$locale}"]   = [$required, 'string', 'max:255'];
                $rules["content_translations.{$locale}"] = [$required, 'string'];
            }
        } else {
            $rules['title']   = ['required', 'string', 'max:255'];
            $rules['content'] = ['required', 'string'];
        }

        $validated = $request->validate($rules);

        // Build title/content from translations or plain fields
        if ($isMultilingual) {
            $titleTranslations   = $request->input('title_translations', []);
            $contentTranslations = $request->input('content_translations', []);
            // Fallback plain fields from default locale
            $title   = $titleTranslations[$defaultLocale] ?? reset($titleTranslations) ?? '';
            $content = $contentTranslations[$defaultLocale] ?? reset($contentTranslations) ?? '';
        } else {
            $title               = $request->input('title');
            $content             = $request->input('content');
            $titleTranslations   = null;
            $contentTranslations = null;
        }

        $slug = $request->filled('slug')
            ? Str::slug($request->input('slug'))
            : Str::slug($title);

        // Ensure unique slug
        $originalSlug = $slug;
        $count = 1;
        while (News::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $imagePath = basename($imagePath);
        }

        News::create([
            'title'                => $title,
            'slug'                 => $slug,
            'content'              => $content,
            'excerpt'              => $request->input('excerpt'),
            'image'                => $imagePath,
            'author_id'            => Auth::id(),
            'category_id'          => $request->input('category_id'),
            'is_published'         => $request->boolean('is_published'),
            'published_at'         => $request->input('is_published') ? ($request->input('published_at') ?? now()) : null,
            'title_translations'   => $titleTranslations,
            'content_translations' => $contentTranslations,
        ]);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'News article created successfully.');
    }

    public function edit(News $news)
    {
        $categories     = NewsCategory::where('is_active', true)->orderBy('name')->get();
        $activeLocales  = LocalizationService::getActiveLocales();
        $defaultLocale  = LocalizationService::getDefaultLocale();
        $localeNames    = LocalizationService::getLocaleNames();
        $isMultilingual = LocalizationService::isMultilingualEnabled();

        return view('admin::news.edit', compact(
            'news', 'categories', 'activeLocales', 'defaultLocale', 'localeNames', 'isMultilingual'
        ));
    }

    public function update(Request $request, News $news)
    {
        $activeLocales  = LocalizationService::getActiveLocales();
        $defaultLocale  = LocalizationService::getDefaultLocale();
        $isMultilingual = LocalizationService::isMultilingualEnabled();

        $rules = [
            'category_id'  => ['nullable', 'exists:news_category,id'],
            'image'        => ['nullable', 'image', 'max:4096'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'slug'         => ['nullable', 'string', 'max:255', "unique:news,slug,{$news->id}"],
        ];

        if ($isMultilingual) {
            foreach ($activeLocales as $locale) {
                $required = $locale === $defaultLocale ? 'required' : 'nullable';
                $rules["title_translations.{$locale}"]   = [$required, 'string', 'max:255'];
                $rules["content_translations.{$locale}"] = [$required, 'string'];
            }
        } else {
            $rules['title']   = ['required', 'string', 'max:255'];
            $rules['content'] = ['required', 'string'];
        }

        $request->validate($rules);

        if ($isMultilingual) {
            $titleTranslations   = $request->input('title_translations', []);
            $contentTranslations = $request->input('content_translations', []);
            $title   = $titleTranslations[$defaultLocale] ?? reset($titleTranslations) ?? $news->title;
            $content = $contentTranslations[$defaultLocale] ?? reset($contentTranslations) ?? $news->content;
        } else {
            $title               = $request->input('title');
            $content             = $request->input('content');
            $titleTranslations   = null;
            $contentTranslations = null;
        }

        $slug = $request->filled('slug')
            ? Str::slug($request->input('slug'))
            : $news->slug;

        $imagePath = $news->image;
        if ($request->hasFile('image')) {
            $imagePath = basename($request->file('image')->store('images', 'public'));
        }

        $news->update([
            'title'                => $title,
            'slug'                 => $slug,
            'content'              => $content,
            'excerpt'              => $request->input('excerpt'),
            'image'                => $imagePath,
            'category_id'          => $request->input('category_id'),
            'is_published'         => $request->boolean('is_published'),
            'published_at'         => $request->boolean('is_published')
                ? ($request->input('published_at') ?? $news->published_at ?? now())
                : null,
            'title_translations'   => $titleTranslations,
            'content_translations' => $contentTranslations,
        ]);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'News article updated successfully.');
    }

    public function destroy(News $news)
    {
        $news->delete();

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'News article deleted successfully.');
    }

    public function restore(int $id)
    {
        $news = News::withTrashed()->findOrFail($id);
        $news->restore();

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'News article restored successfully.');
    }
}
