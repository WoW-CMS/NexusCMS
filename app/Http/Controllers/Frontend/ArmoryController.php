<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseController;
use App\Models\Armory;
use Illuminate\Http\Request;

/**
 * Frontend Armory Controller for handling main website pages
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
class ArmoryController extends BaseController
{
    /**
     * Model associated with the controller
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model = Armory::class;

    /**
     * Determines if the controller has a model associated with it
     *
     * @var bool
     */
    protected $hasModel = 'armory';

    /**
     * Determines if it's paginated or not
     *
     * @var bool
     */
    protected $isPaginated = true;

    /**
     * Number of items per page
     *
     * @var int
     */
    protected $perPage = 9;

    /**
     * Default view for the controller
     *
     * @var string
     */
    protected $views = [
        'index' => 'armory.index',
        'show' => 'armory.show',
    ];
    protected function getModelData($perPage = 0)
    {
        return Armory::query()
            ->search(request('q'))
            ->orderBy('name')
            ->paginate($perPage)
            ->appends(['q' => request('q')]);
    }
}
