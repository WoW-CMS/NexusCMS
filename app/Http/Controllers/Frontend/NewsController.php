<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Helpers\GeneralHelper;
use App\Models\NewsCategory;
use App\Services\LocalizationService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

/**
 * Frontend News Controller – supports optional multilingual content.
 */
class NewsController extends Controller
{
    protected $perPage = 5;

    protected $views = [
        'index' => 'news.index',
        'show'  => 'news.show',
    ];

    /**
     * Display a listing of published news articles with optional category filtering.
     */
    public function index(Request $request): View
    {
        $locale   = $this->resolveLocale($request);
        $category = $request->get('category', 'all');

        $query = News::where('is_published', true)->orderBy('created_at', 'desc');
        if ($category !== 'all') {
            $query->where('category_id', $category);
        }

        $items      = (new News)->getCachedList($query, $this->perPage);
        $recentNews = (new News)->recentNews();
        $category   = (new NewsCategory)->getAllCategoriesWithCount();

        $items->each(function ($item) use ($locale) {
            $item->display_title   = $item->translatedTitle($locale);
            $item->display_content = $item->translatedContent($locale);
            $item->display_excerpt = $item->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($item->display_content), 220);
            $item->reading_time    = GeneralHelper::readingTime($item->display_content);
        });

        $recentNews->each(function ($item) use ($locale) {
            $item->display_title = $item->translatedTitle($locale);
        });

        return view($this->views['index'], [
            'data'          => $items,
            'recentNews'    => $recentNews,
            'category'      => $category,
            'activeLocale'  => $locale,
            'activeLocales' => LocalizationService::getActiveLocales(),
            'isMultilingual'=> LocalizationService::isMultilingualEnabled(),
        ]);
    }

    /**
     * Display a single news article by slug.
     */
    public function show(Request $request, string $slug): View
    {
        $locale = $this->resolveLocale($request);
        $item   = (new News)->getCachedByField('slug', $slug);

        if (!$item) abort(404);

        $item->load(['author', 'comments' => function ($query) {
            $query->where('is_active', true)
                ->with('user')
                ->orderBy('created_at', 'desc');
        }]);

        $item->display_title   = $item->translatedTitle($locale);
        $item->display_content = $item->translatedContent($locale);
        $item->display_excerpt = $item->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($item->display_content), 300);
        $item->reading_time    = GeneralHelper::readingTime($item->display_content);

        return view($this->views['show'], [
            'item'          => $item,
            'activeLocale'  => $locale,
            'activeLocales' => LocalizationService::getActiveLocales(),
            'isMultilingual'=> LocalizationService::isMultilingualEnabled(),
        ]);
    }

    /**
     * Resolve locale from: query param → session → default locale.
     * Persists chosen locale in session.
     */
    private function resolveLocale(Request $request): string
    {
        $available     = LocalizationService::getActiveLocales();
        $defaultLocale = LocalizationService::getDefaultLocale();

        if (!LocalizationService::isMultilingualEnabled()) {
            return $defaultLocale;
        }

        // Accept locale switch via ?lang=xx
        if ($request->has('lang') && in_array($request->input('lang'), $available)) {
            $locale = $request->input('lang');
            session(['news_locale' => $locale]);
            return $locale;
        }

        $sessionLocale = session('news_locale');
        if ($sessionLocale && in_array($sessionLocale, $available)) {
            return $sessionLocale;
        }

        return $defaultLocale;
    }
}

