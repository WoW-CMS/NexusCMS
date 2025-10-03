<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\View\View;

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
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $items = News::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view($this->views['index'], ['data' => $items]);
    }

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
            $item = News::where('slug', $id)
                ->with(['comments' => function ($query) {
                    $query->where('is_active', true)
                        ->with('user')
                        ->orderBy('created_at', 'desc');
                }])
                ->firstOrFail();

            if (request()->expectsJson()) {
                return response()->json(['data' => $item], 200);
            }

            return view($this->views['show'], ['item' => $item]);

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Record not found'], 404);
            }

            return view('errors.404', ['message' => 'Record not found']);
        }
    }
}
