<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Helpers\RealmHelper;
use App\Models\News;

/**
 * Frontend Home Controller for handling main website pages
 *
 * This controller handles the main frontend pages and views for the website,
 * including the homepage, landing pages, and other public-facing content.
 *
 * @category Controllers
 * @package  App\Http\Controllers\Frontend
 * @author   NexusCMS <noreply@wow-cms.com>
 * @license  GNU General Public License (GPL)
 * @version  1.0.0
 * @link     wow-cms.com
 */
class HomeController extends BaseController
{
    /**
     * Default view for the homepage.
     */
    protected $views = [
        'index' => 'home.index',
    ];

    /**
     * Has model flag.
     *
     * @var bool
     */
    protected $hasModel = true;

    /**
     * Model name.
     *
     * @var string
     */
    protected $model = 'news';

    /**
     * Is paginated flag.
     *
     * @var bool
     */
    protected $isPaginated = true;

    /**
     * perPage
     *
     * @var int
     */
    protected $perPage = 2;

    public function index(Request $request, ?string $view = null)
    {
        $realms = RealmHelper::all();
        $perPage = $request->get('per_page', $this->perPage);
        $news = $this->getModelData($perPage);

        $data = [
            'realms' => $realms,
            'news'   => $news,
        ];

        return $this->renderView('home.index', compact('data'));
    }
}
