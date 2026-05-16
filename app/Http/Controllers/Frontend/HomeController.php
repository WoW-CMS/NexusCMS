<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Helpers\RealmHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Libraries\Redis\RedisLibrary;
use App\Models\News;

/**
 * Handles frontend home and public-facing pages.
 *
 * This controller manages the homepage and other primary
 * frontend views. It retrieves realm and news data, prepares
 * it for rendering, and delegates view rendering.
 *
 * @category Controllers
 * @package  App\Http\Controllers\Frontend
 * @since    1.0.0
 */
class HomeController extends Controller
{
    /**
     * Default views for the home pages.
     *
     * @var array<string, string>
     */
    protected $views = [
        'index' => 'home.index',
        'howtoplay' => 'howtoplay.index',
    ];

    /**
     * Default pagination limit.
     *
     * @var int
     */
    protected $perPage = 5;

    /**
     * Display the homepage.
     *
     * Retrieves available realms and paginated news entries,
     * then renders the homepage view.
     *
     * @param Request     $request
     * @param string|null $view
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, \App\Libraries\Redis\RedisLibrary $redis, ?string $view = null)
    {
        $realms = RealmHelper::all();
        $perPage = $request->get('per_page', $this->perPage);
        $page = (int) ($request->get('page', 1));

        // Single cache key for consistent home payload
        $cacheKey = "home:news:page={$page}:perpage={$perPage}";
        $cached = $redis->get($cacheKey);

        if ($cached && is_array($cached) && isset($cached['news'], $cached['featured'])) {
            $news = $cached['news'];
            $featuredNews = $cached['featured'];
        } else {
            $allNews = \App\Models\News::query()
                ->where('is_published', true)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            $featuredNews = $allNews->shift();
            $news = $allNews;

            // Cache the unified payload
            $redis->set($cacheKey, [
                'news' => $news,
                'featured' => $featuredNews,
            ], 60);
        }
    
        $data = [
            'realms' => $realms,
            'featuredNews' => $featuredNews,
            'news'   => $news,
        ];
    
        return view($this->views['index'], [ 'data' => $data ]);
    }
    
    /**
     * Display the "How to Play" page.
     *
     * @param Request     $request
     * @param string|null $view
     * @return \Illuminate\Contracts\View\View
     */
    public function howToPlay(Request $request, ?string $view = null)
    {
        return view($this->views['howtoplay']);
    }
}
