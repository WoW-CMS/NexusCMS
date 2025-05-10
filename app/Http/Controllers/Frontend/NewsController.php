<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseController;

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
     * Default view for the controller
     *
     * @var string
     */
    protected $view = 'news.index';
}
