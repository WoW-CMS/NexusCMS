<?php

namespace App\Modules\Home\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class HomeController extends BaseController
{
    /**
     * Default view for the homepage.
     */
    protected $views = [
        'index' => 'home::index',
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
    protected $model = 'News';

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
}