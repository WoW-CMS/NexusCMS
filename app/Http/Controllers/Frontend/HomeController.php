<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Helpers\RealmHelper;

/**
 * Handles frontend home and public-facing pages.
 *
 * This controller manages the homepage and other primary
 * frontend views. It retrieves realm and news data, prepares
 * it for rendering, and delegates view rendering to the
 * {@see BaseController}.
 *
 * @category Controllers
 * @package  App\Http\Controllers\Frontend
 * @author   NexusCMS <noreply@wow-cms.com>
 * @license  GNU General Public License (GPL)
 * @version  1.0.0
 * @link     https://wow-cms.com
 * @since    1.0.0
 * @api
 */
class HomeController extends BaseController
{
    /**
     * Default views for the home pages.
     *
     * @var array<string, string> Associative array mapping actions to Blade views
     * @since 1.0.0
     */
    protected $views = [
        'index' => 'home.index',
    ];

    /**
     * Indicates if this controller has an associated model.
     *
     * @var bool
     * @since 1.0.0
     */
    protected $hasModel = true;

    /**
     * The model name associated with this controller.
     *
     * Used for retrieving and rendering model-specific data.
     *
     * @var string
     * @since 1.0.0
     */
    protected $model = 'news';

    /**
     * Indicates if model data should be paginated.
     *
     * @var bool
     * @since 1.0.0
     */
    protected $isPaginated = true;

    /**
     * The default number of items per page.
     *
     * @var int
     * @since 1.0.0
     */
    protected $perPage = 2;

    /**
     * Display the homepage.
     *
     * Retrieves available realms and paginated news entries, compiles
     * the data, and renders the homepage view.
     *
     * @param Request     $request The incoming HTTP request
     * @param string|null $view    Optional custom view to render
     * @return \Illuminate\View\View The rendered homepage view
     * @since 1.0.0
     */
    public function index(Request $request, ?string $view = null)
    {
        $realms = RealmHelper::all();
        $perPage = $request->get('per_page', $this->perPage);
        $news = $this->getModelData($perPage);

        $data = [
            'realms' => $realms,
            'news'   => $news,
        ];

        return $this->renderView($view ?? $this->views['index'], compact('data'));
    }
}
