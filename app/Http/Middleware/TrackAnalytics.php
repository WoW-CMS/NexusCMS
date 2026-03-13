<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AnalyticsSession;
use App\Models\AnalyticsPageView;
use Symfony\Component\HttpFoundation\Response;

class TrackAnalytics
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Don't track admin panel, API, or debug bar requests
        if ($this->shouldTrack($request)) {
            [$visitorId, $isNew] = $this->getOrCreateVisitorId($request);
            $this->trackPageView($request, $visitorId);

            if ($isNew) {
                $response->headers->setCookie(
                    cookie('analytics_visitor_id', $visitorId, 60 * 24 * 365, '/', null, false, true)
                );
            }
        }

        return $response;
    }

    /**
     * Determine if the request should be tracked
     */
    protected function shouldTrack(Request $request): bool
    {
        // Skip admin routes, API routes, and static assets
        $path = $request->path();

        $skipPaths = [
            'acp',  // Admin control panel
            'api',  // API routes
            'admin',
        ];

        foreach ($skipPaths as $skipPath) {
            if (str_starts_with($path, $skipPath)) {
                return false;
            }
        }

        // Skip if user agent is a bot
        if ($this->isBot($request->userAgent())) {
            return false;
        }

        return true;
    }

    /**
     * Track a page view
     */
    protected function trackPageView(Request $request, string $visitorId): void
    {
        try {
            // visitorId is passed in from handle()

            // Get or create session
            $session = AnalyticsSession::firstOrCreate(
                ['visitor_id' => $visitorId],
                [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'is_bot' => $this->isBot($request->userAgent()),
                ]
            );

            // Increment page views count
            $session->increment('page_views');
            $session->touch('updated_at');

            // Create page view record
            AnalyticsPageView::create([
                'session_id' => $session->id,
                'page_url' => $request->path(),
                'page_title' => $this->getPageTitle($request),
                'referrer' => $request->headers->get('referer'),
                'time_on_page' => 0, // Will be updated on next page view
                'bounced' => false,
            ]);
        } catch (\Exception $e) {
            // Silently fail - analytics tracking shouldn't break the app
            \Log::debug('Analytics tracking failed: ' . $e->getMessage());
        }
    }

    /**
     * Get or create a visitor ID from cookie.
     * Returns [visitorId, isNew].
     */
    protected function getOrCreateVisitorId(Request $request): array
    {
        $visitorId = $request->cookie('analytics_visitor_id');

        if ($visitorId) {
            return [$visitorId, false];
        }

        $visitorId = 'visitor_' . bin2hex(random_bytes(16));
        return [$visitorId, true];
    }

    /**
     * Get page title from request
     */
    protected function getPageTitle(Request $request): string
    {
        // Try to extract title from route
        $route = $request->route();

        if ($route && $route->getName()) {
            return ucwords(str_replace('.', ' ', $route->getName()));
        }

        // Fallback to path
        return ucwords(str_replace('/', ' ', trim($request->path(), '/'))) ?: 'Home';
    }

    /**
     * Check if user agent is a bot
     */
    protected function isBot(string $userAgent): bool
    {
        $botPatterns = [
            'bot',
            'crawler',
            'spider',
            'scraper',
            'curl',
            'wget',
            'python',
            'java',
            'perl',
            'ruby',
            'google',
            'bing',
            'yandex',
            'baidu',
            'facebook',
            'twitter',
            'linkedin',
        ];

        $userAgent = strtolower($userAgent);

        foreach ($botPatterns as $pattern) {
            if (str_contains($userAgent, $pattern)) {
                return true;
            }
        }

        return false;
    }
}
