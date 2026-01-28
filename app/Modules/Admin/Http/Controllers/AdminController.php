<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Admin\Services\ApiService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index(Request $request)
    {
        $latestRelease = $this->apiService->latestRelease();
        $checkVersion = $this->apiService->checkVersion('wow-cms', 'nexuscms', config('app.version'));

        $outdated = $checkVersion['outdated'];

        return view('admin::index', compact('latestRelease', 'checkVersion', 'outdated'));  
    }
}
