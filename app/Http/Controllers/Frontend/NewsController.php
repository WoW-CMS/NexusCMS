<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseController;
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
class NewsController extends BaseController
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
    protected $perPage = 1;

    /**
     * Default view for the controller
     *
     * @var string
     */
    protected $views = [
        'index' => 'news.index',
        'show' => 'news.show',
    ];

    /**
     * Show specific resource
     *
     * @param int $id
     * @param string|null $view
     * @return JsonResponse|View
     */
    public function show(int|string $id, ?string $view = null)
    {
        try {
            // Buscar la noticia por slug o id
            $item = News::where('slug', $id)
                ->with(['comments' => function ($query) {
                    $query->where('is_active', true)
                        ->with('user')
                        ->orderBy('created_at', 'asc');
                }])
                ->firstOrFail();

            if (request()->expectsJson()) {
                return $this->successResponse($item);
            }

            $this->setView('show');
            return $this->renderView($view ?? $this->getCurrentView(), ['item' => $item]);
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return $this->errorResponse('Record not found', 404);
            }

            return $this->renderView('errors.404', ['message' => 'Record not found']);
        }
    }
}
