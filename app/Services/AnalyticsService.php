<?php

namespace App\Services;

use App\Models\AnalyticsSession;
use App\Models\AnalyticsPageView;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get analytics data for a given period
     */
    public function getAnalyticsData(string $period = '30d'): array
    {
        $startDate = $this->getStartDate($period);

        return [
            'period' => $period,
            'start_date' => $startDate,
            'total_visitors' => $this->getTotalVisitors($startDate),
            'page_views' => $this->getPageViews($startDate),
            'avg_session_duration' => $this->getAverageSessionDuration($startDate),
            'bounce_rate' => $this->getBounceRate($startDate),
            'top_pages' => $this->getTopPages($startDate),
            'visitor_growth' => $this->getVisitorGrowth($startDate),
            'traffic_by_device' => $this->getTrafficByDevice($startDate),
            'traffic_by_source' => $this->getTrafficBySource($startDate),
        ];
    }

    /**
     * Get the start date based on period
     */
    public function getStartDate(string $period): Carbon
    {
        return match($period) {
            '7d' => Carbon::now()->subDays(7),
            '90d' => Carbon::now()->subDays(90),
            '1y' => Carbon::now()->subYear(),
            default => Carbon::now()->subDays(30),
        };
    }

    /**
     * Get total visitors count
     */
    public function getTotalVisitors(Carbon $startDate): int
    {
        return AnalyticsSession::where('created_at', '>=', $startDate)
            ->where('is_bot', false)
            ->distinct('visitor_id')
            ->count('visitor_id');
    }

    /**
     * Get total page views
     */
    public function getPageViews(Carbon $startDate): int
    {
        return AnalyticsPageView::whereHas('session', function ($query) use ($startDate) {
            $query->where('created_at', '>=', $startDate)
                  ->where('is_bot', false);
        })->count();
    }

    /**
     * Get average session duration in seconds
     */
    public function getAverageSessionDurationSeconds(Carbon $startDate): int
    {
        return (int) AnalyticsSession::where('created_at', '>=', $startDate)
            ->where('is_bot', false)
            ->avg('duration_seconds') ?? 0;
    }

    /**
     * Get average session duration formatted
     */
    public function getAverageSessionDuration(Carbon $startDate): string
    {
        return $this->formatSeconds($this->getAverageSessionDurationSeconds($startDate));
    }

    /**
     * Get bounce rate percentage
     */
    public function getBounceRate(Carbon $startDate): float
    {
        $totalPageViews = AnalyticsPageView::whereHas('session', function ($query) use ($startDate) {
            $query->where('created_at', '>=', $startDate)
                  ->where('is_bot', false);
        })->count();

        if ($totalPageViews === 0) {
            return 0;
        }

        $bouncedPageViews = AnalyticsPageView::where('bounced', true)
            ->whereHas('session', function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate)
                      ->where('is_bot', false);
            })->count();

        return round(($bouncedPageViews / $totalPageViews) * 100, 2);
    }

    /**
     * Get top pages with analytics data
     */
    public function getTopPages(Carbon $startDate, int $limit = 10): array
    {
        return AnalyticsPageView::select(
            'page_url as url',
            'page_title as title',
            DB::raw('COUNT(*) as views'),
            DB::raw('COUNT(DISTINCT session_id) as unique_visitors'),
            DB::raw('AVG(time_on_page) as avg_duration'),
            DB::raw('SUM(CASE WHEN bounced = 1 THEN 1 ELSE 0 END) / COUNT(*) * 100 as bounce_rate')
        )
        ->whereHas('session', function ($query) use ($startDate) {
            $query->where('created_at', '>=', $startDate)
                  ->where('is_bot', false);
        })
        ->groupBy('page_url', 'page_title')
        ->orderByDesc('views')
        ->limit($limit)
        ->get()
        ->map(fn($page) => [
            'url' => $page->url,
            'title' => $page->title,
            'views' => (int)$page->views,
            'unique_visitors' => (int)$page->unique_visitors,
            'avg_duration' => $this->formatSeconds((int)$page->avg_duration),
            'bounce_rate' => round($page->bounce_rate ?? 0, 2),
        ])
        ->toArray();
    }

    /**
     * Get visitor growth over time
     */
    public function getVisitorGrowth(Carbon $startDate): array
    {
        $days = now()->diffInDays($startDate) ?: 30;

        return AnalyticsSession::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(DISTINCT visitor_id) as visitors')
        )
        ->where('created_at', '>=', $startDate)
        ->where('is_bot', false)
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->map(fn($row) => [
            'date' => $row->date,
            'visitors' => (int)$row->visitors,
        ])
        ->toArray();
    }

    /**
     * Get traffic by device type
     */
    public function getTrafficByDevice(Carbon $startDate): array
    {
        $total = AnalyticsSession::where('created_at', '>=', $startDate)
            ->where('is_bot', false)
            ->count();

        if ($total === 0) {
            return [];
        }

        // Parse device types from user agent without loading all sessions in memory.
        $devices = [];

        AnalyticsSession::query()
            ->where('created_at', '>=', $startDate)
            ->where('is_bot', false)
            ->select(['id', 'user_agent'])
            ->orderBy('id')
            ->chunkById(1000, function ($sessions) use (&$devices): void {
                foreach ($sessions as $session) {
                    $device = $this->detectDevice((string) $session->user_agent);
                    $devices[$device] = ($devices[$device] ?? 0) + 1;
                }
            });

        return collect($devices)
            ->map(function ($count, $device) use ($total) {
                return [
                    'device' => $device,
                    'count' => $count,
                    'percentage' => round(($count / $total) * 100, 2),
                ];
            })
                ->filter(fn ($row) => $row['count'] > 0)
            ->toArray();
    }

    /**
     * Get traffic by source (referrer)
     */
    public function getTrafficBySource(Carbon $startDate): array
    {
        $total = AnalyticsPageView::whereHas('session', function ($query) use ($startDate) {
            $query->where('created_at', '>=', $startDate)
                  ->where('is_bot', false);
        })->count();

        if ($total === 0) {
            return [];
        }

        return AnalyticsPageView::select(
            DB::raw('COALESCE(referrer, "direct") as source'),
            DB::raw('COUNT(*) as count')
        )
        ->whereHas('session', function ($query) use ($startDate) {
            $query->where('created_at', '>=', $startDate)
                  ->where('is_bot', false);
        })
        ->groupBy('source')
        ->orderByDesc('count')
        ->limit(10)
        ->get()
        ->map(fn($row) => [
            'source' => $row->source,
            'count' => (int)$row->count,
            'percentage' => round(($row->count / $total) * 100, 2),
        ])
        ->toArray();
    }

    /**
     * Format seconds to MM:SS format
     */
    public function formatSeconds(int $seconds): string
    {
        $minutes = intdiv($seconds, 60);
        $secs = $seconds % 60;

        return sprintf('%d:%02d', $minutes, $secs);
    }

    /**
     * Detect device type from user agent
     */
    protected function detectDevice(string $userAgent): string
    {
        $userAgent = strtolower($userAgent);

        if (str_contains($userAgent, 'mobile') || str_contains($userAgent, 'android')) {
            return 'Mobile';
        }

        if (str_contains($userAgent, 'tablet') || str_contains($userAgent, 'ipad')) {
            return 'Tablet';
        }

        return 'Desktop';
    }
}
