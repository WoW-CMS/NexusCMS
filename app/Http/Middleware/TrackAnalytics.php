<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Jobs\TrackAnalyticsPageView;
use Symfony\Component\HttpFoundation\Response;

class TrackAnalytics
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $shouldTrack = $this->shouldTrack($request);
        $isNew = false;

        if ($shouldTrack) {
            [$visitorId, $isNew] = $this->getOrCreateVisitorId($request);
            $request->attributes->set('analytics.track', true);
            $request->attributes->set('analytics.visitor_id', $visitorId);
        }

        $response = $next($request);

        if ($shouldTrack && $isNew) {
            if ($isNew) {
                $response->headers->setCookie(
                    cookie('analytics_visitor_id', $visitorId, 60 * 24 * 365, '/', null, false, true)
                );
            }
        }

        return $response;
    }

    /**
     * Persist analytics after the response has been sent.
     */
    public function terminate(Request $request, Response $response): void
    {
        if (!$request->attributes->get('analytics.track', false)) {
            return;
        }

        $visitorId = $request->attributes->get('analytics.visitor_id');

        if (is_string($visitorId) && $visitorId !== '') {
            $this->trackPageView($request, $visitorId);
        }
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
     * Track a page view via queued job.
     */
    protected function trackPageView(Request $request, string $visitorId): void
    {
        try {
            TrackAnalyticsPageView::dispatch(
                visitorId: $visitorId,
                ipAddress: (string) $request->ip(),
                userAgent: (string) $request->userAgent(),
                pagePath: $request->path(),
                pageTitle: $this->getPageTitle($request),
                referrer: $request->headers->get('referer'),
            );
        } catch (\Exception $e) {
            \Log::debug('Analytics dispatch failed: ' . $e->getMessage());
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
