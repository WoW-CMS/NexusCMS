<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\AnalyticsSession;
use App\Models\AnalyticsPageView;

class TrackAnalyticsPageView implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $visitorId,
        private string $ipAddress,
        private string $userAgent,
        private string $pagePath,
        private ?string $pageTitle,
        private ?string $referrer,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $session = AnalyticsSession::firstOrCreate(
                ['visitor_id' => $this->visitorId],
                [
                    'ip_address' => $this->ipAddress,
                    'user_agent' => $this->userAgent,
                    'is_bot' => false,
                ]
            );

            $session->increment('page_views');
            $session->touch('updated_at');

            AnalyticsPageView::create([
                'session_id' => $session->id,
                'page_url' => $this->pagePath,
                'page_title' => $this->pageTitle,
                'referrer' => $this->referrer,
                'time_on_page' => 0,
                'bounced' => false,
            ]);
        } catch (\Throwable $e) {
            \Log::debug('Analytics job failed: ' . $e->getMessage());
        }
    }
}
