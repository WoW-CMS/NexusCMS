<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

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
    protected $view = 'home/home';

    /**
     * Display the homepage
     *
     * @param Request $request The request instance
     * @return \Illuminate\View\View
     */
    public function index(Request $request, ?string $view = null)
    {
        return $this->renderView();
    }
}
