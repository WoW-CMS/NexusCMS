<?php

namespace Tests\Feature;

use App\Models\AnalyticsPageView;
use App\Models\AnalyticsSession;
use App\Services\AnalyticsService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_start_date_uses_period_rules(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 3, 23, 0, 0, 0));

        $service = new AnalyticsService();

        $this->assertSame('2026-03-16', $service->getStartDate('7d')->toDateString());
        $this->assertSame('2025-12-23', $service->getStartDate('90d')->toDateString());
        $this->assertSame('2025-03-23', $service->getStartDate('1y')->toDateString());
        $this->assertSame('2026-02-21', $service->getStartDate('invalid')->toDateString());

        Carbon::setTestNow();
    }

    public function test_format_seconds_returns_mm_ss(): void
    {
        $service = new AnalyticsService();

        $this->assertSame('0:00', $service->formatSeconds(0));
        $this->assertSame('1:05', $service->formatSeconds(65));
        $this->assertSame('10:00', $service->formatSeconds(600));
    }

    public function test_analytics_aggregates_ignore_bots_and_compute_metrics(): void
    {
        $startDate = now()->subDays(30);

        $humanA = AnalyticsSession::create([
            'visitor_id' => 'v-1',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0)',
            'duration_seconds' => 120,
            'page_views' => 2,
            'is_bot' => false,
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        $humanB = AnalyticsSession::create([
            'visitor_id' => 'v-2',
            'ip_address' => '127.0.0.2',
            'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS) mobile',
            'duration_seconds' => 60,
            'page_views' => 1,
            'is_bot' => false,
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        AnalyticsSession::create([
            'visitor_id' => 'v-bot',
            'ip_address' => '127.0.0.3',
            'user_agent' => 'Googlebot',
            'duration_seconds' => 999,
            'page_views' => 10,
            'is_bot' => true,
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        AnalyticsPageView::create([
            'session_id' => $humanA->id,
            'page_url' => '/news',
            'page_title' => 'News',
            'referrer' => 'google.com',
            'time_on_page' => 40,
            'bounced' => false,
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        AnalyticsPageView::create([
            'session_id' => $humanA->id,
            'page_url' => '/forum',
            'page_title' => 'Forum',
            'referrer' => null,
            'time_on_page' => 20,
            'bounced' => true,
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        AnalyticsPageView::create([
            'session_id' => $humanB->id,
            'page_url' => '/news',
            'page_title' => 'News',
            'referrer' => 'x.com',
            'time_on_page' => 50,
            'bounced' => false,
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        $service = new AnalyticsService();

        $this->assertSame(2, $service->getTotalVisitors($startDate));
        $this->assertSame(3, $service->getPageViews($startDate));
        $this->assertSame(33.33, $service->getBounceRate($startDate));
        $this->assertSame('1:30', $service->getAverageSessionDuration($startDate));

        $topPages = $service->getTopPages($startDate, 10);
        $this->assertNotEmpty($topPages);
        $this->assertArrayHasKey('url', $topPages[0]);
        $this->assertArrayHasKey('views', $topPages[0]);

        $byDevice = $service->getTrafficByDevice($startDate);
        $this->assertNotEmpty($byDevice);

        $bySource = $service->getTrafficBySource($startDate);
        $this->assertNotEmpty($bySource);

        $analytics = $service->getAnalyticsData('30d');
        $this->assertSame('30d', $analytics['period']);
        $this->assertArrayHasKey('total_visitors', $analytics);
        $this->assertArrayHasKey('traffic_by_device', $analytics);
        $this->assertArrayHasKey('traffic_by_source', $analytics);
    }
}
