<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Admin\Services\UpdateService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(protected UpdateService $updateService) {}

    public function index(Request $request)
    {
        $latestRelease = $this->updateService->getLatestRelease();
        $outdated      = $this->updateService->isOutdated($latestRelease);

        return view('admin::index', compact('latestRelease', 'outdated'));
    }
}
