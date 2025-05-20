<?php

namespace App\Modules\News\Controllers;

use App\Http\Controllers\BaseController;

class NewsController extends BaseController
{
    /**
     * Model name
     *
     * @var string
     */
    protected $model = 'News';

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
        'show' => 'news.list',
    ];
}