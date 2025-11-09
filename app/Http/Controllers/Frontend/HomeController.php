<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Helpers\RealmHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
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
    protected $perPage = 2;

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
    public function index(Request $request, ?string $view = null)
    {
        $realms = RealmHelper::all();
        $perPage = $request->get('per_page', $this->perPage);
    
        $allNews = \App\Models\News::query()
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

        $featuredNews = $allNews->shift();
        $news = $allNews;
    
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
