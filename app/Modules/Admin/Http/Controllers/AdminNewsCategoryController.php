<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AdminNewsCategoryController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', NewsCategory::class);

        $categories = NewsCategory::withCount('news')
            ->withTrashed()
            ->orderBy('order')
            ->orderBy('name')
            ->paginate(20);

        return view('admin::news.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('create', NewsCategory::class);

        return view('admin::news.categories.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', NewsCategory::class);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'slug'        => ['nullable', 'string', 'max:150', 'unique:news_category,slug'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active'   => ['boolean'],
            'order'       => ['nullable', 'integer', 'min:0'],
        ]);

        $slug = $validated['slug']
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        // Ensure unique slug
        $originalSlug = $slug;
        $count = 1;
        while (NewsCategory::withTrashed()->where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        NewsCategory::create([
            'name'        => $validated['name'],
            'slug'        => $slug,
            'description' => $validated['description'] ?? null,
            'is_active'   => $request->boolean('is_active', true),
            'order'       => $validated['order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.news.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(NewsCategory $newsCategory)
    {
        $this->authorize('update', $newsCategory);

        return view('admin::news.categories.edit', compact('newsCategory'));
    }

    public function update(Request $request, NewsCategory $newsCategory)
    {
        $this->authorize('update', $newsCategory);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'slug'        => ['nullable', 'string', 'max:150', "unique:news_category,slug,{$newsCategory->id}"],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active'   => ['boolean'],
            'order'       => ['nullable', 'integer', 'min:0'],
        ]);

        $slug = $validated['slug']
            ? Str::slug($validated['slug'])
            : $newsCategory->slug;

        $newsCategory->update([
            'name'        => $validated['name'],
            'slug'        => $slug,
            'description' => $validated['description'] ?? null,
            'is_active'   => $request->boolean('is_active'),
            'order'       => $validated['order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.news.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(NewsCategory $newsCategory)
    {
        $this->authorize('delete', $newsCategory);

        if ($newsCategory->news()->count() > 0) {
            return redirect()
                ->route('admin.news.categories.index')
                ->with('error', 'Cannot delete a category that has news articles.');
        }

        $newsCategory->delete();

        return redirect()
            ->route('admin.news.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
