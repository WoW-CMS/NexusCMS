<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Helpers\GeneralHelper;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
/**
 * Frontend Home Controller for handling main website pages
 */
class NewsController extends Controller
{
    /**
     * Model name
     *
     * @var string
     */
    protected $model = 'news';

    /**
     * Is paginated
     *
     * @var boolean
     */
    protected $isPaginated = true;

    /**
     * Per page
     */
    protected $perPage = 5;

    /**
     * Default view for the controller
     *
     * @var string
     */
    protected $views = [
        'index' => 'news.index',
        'show' => 'news.show',
    ];

    public function index(Request $request)
    {
        $category = $request->get('category', 'all');
        $query = News::where('is_published', true)->orderBy('created_at', 'desc');
        if ($category != 'all') {
            $query->where('category_id', $category);
        }
        $items = (new News)->getCachedList($query, $this->perPage);
        $recentNews = (new News)->recentNews();
        $category = (new NewsCategory)->getAllCategoriesWithCount();

        $items->each(function ($item) {
            $item->reading_time = GeneralHelper::readingTime($item->content);
        });

        return view($this->views['index'], ['data' => $items, 'recentNews' => $recentNews, 'category' => $category]);
    }

    public function show(string $slug, ?string $view = null)
    {
        $item = (new News)->getCachedByField('slug', $slug);
        if (!$item) abort(404);

        $item->load(['comments' => function ($query) {
            $query->where('is_active', true)
                ->with('user')
                ->orderBy('created_at', 'desc');
        }]);

        $item->reading_time = GeneralHelper::readingTime($item->content);

        return view($this->views['show'], ['item' => $item]);
    }
}
