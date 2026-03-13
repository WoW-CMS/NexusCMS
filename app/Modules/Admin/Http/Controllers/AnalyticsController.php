<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Display the analytics dashboard
     */
    public function index(Request $request)
    {
        $period = $request->query('period', '30d');
        $startDate = $this->analyticsService->getStartDate($period);

        $totalVisitors = $this->analyticsService->getTotalVisitors($startDate);
        $pageViews = $this->analyticsService->getPageViews($startDate);
        $avgSessionDuration = $this->analyticsService->getAverageSessionDuration($startDate);
        $bounceRate = $this->analyticsService->getBounceRate($startDate);
        $topPages = $this->analyticsService->getTopPages($startDate);

        return view('admin::analytics.index', compact(
            'topPages',
            'totalVisitors',
            'pageViews',
            'avgSessionDuration',
            'bounceRate',
            'period'
        ));
    }
}
