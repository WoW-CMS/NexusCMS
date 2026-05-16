<?php

namespace Modules\Forum\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Forum\Domain\Models\Forum;

class ForumAdminController extends Controller
{
    public function index(): View
    {
        $forums = Forum::query()
            ->with('parent')
            ->orderBy('order')
            ->get();

        return view('forum-admin::index', compact('forums'));
    }

    public function create(): View
    {
        $categories = Forum::query()
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return view('forum-admin::create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'slug'        => ['required', 'string', 'max:255', 'unique:forums,slug'],
            'parent_id'   => ['nullable', 'integer', 'exists:forums,id'],
            'order'       => ['nullable', 'integer', 'min:0'],
            'is_category' => ['boolean'],
        ]);

        $validated['is_category'] = $request->boolean('is_category');
        $validated['order'] = (int) ($validated['order'] ?? 0);

        Forum::query()->create($validated);

        return redirect()
            ->route('admin.forum.index')
            ->with('success', 'Forum created successfully.');
    }

    public function edit(Forum $forum): View
    {
        $categories = Forum::query()
            ->whereNull('parent_id')
            ->where('id', '!=', $forum->id)
            ->orderBy('order')
            ->get();

        return view('forum-admin::edit', compact('forum', 'categories'));
    }

    public function update(Request $request, Forum $forum): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'slug'        => ['required', 'string', 'max:255', 'unique:forums,slug,' . $forum->id],
            'parent_id'   => ['nullable', 'integer', 'exists:forums,id'],
            'order'       => ['nullable', 'integer', 'min:0'],
            'is_category' => ['boolean'],
        ]);

        $validated['is_category'] = $request->boolean('is_category');
        $validated['order'] = (int) ($validated['order'] ?? 0);

        $forum->update($validated);

        return redirect()
            ->route('admin.forum.index')
            ->with('success', 'Forum updated successfully.');
    }

    public function destroy(Forum $forum): RedirectResponse
    {
        if ($forum->threads()->exists()) {
            return redirect()
                ->route('admin.forum.index')
                ->with('error', 'Cannot delete a forum that has threads.');
        }

        if ($forum->subforums()->exists()) {
            return redirect()
                ->route('admin.forum.index')
                ->with('error', 'Cannot delete a forum that has sub-forums.');
        }

        $forum->delete();

        return redirect()
            ->route('admin.forum.index')
            ->with('success', 'Forum deleted.');
    }
}
