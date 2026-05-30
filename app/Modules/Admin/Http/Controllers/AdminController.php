<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SystemInfoService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(
        protected SystemInfoService $systemInfo,
    ) {}

    public function index(Request $request)
    {
        $systemInfo    = $this->systemInfo->getInfo();
        $isDebug       = $this->systemInfo->isDebugEnabled();

        return view('admin::index', compact('systemInfo', 'isDebug'));
    }
}
