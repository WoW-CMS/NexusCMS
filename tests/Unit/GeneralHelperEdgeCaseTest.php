<?php

namespace Tests\Unit;

use App\Helpers\GeneralHelper;
use PHPUnit\Framework\TestCase;

class GeneralHelperEdgeCaseTest extends TestCase
{
    public function test_reading_time_ignores_html_and_honors_custom_wpm(): void
    {
        $html = '<p>' . str_repeat('word ', 300) . '</p>';

        $minutes = GeneralHelper::readingTime($html, 150);

        $this->assertSame(2, $minutes);
    }

    public function test_share_social_returns_default_for_unknown_network(): void
    {
        $result = GeneralHelper::shareSocial('mastodon', 'https://example.com', 'Title');

        $this->assertSame('#', $result['url']);
        $this->assertSame('', $result['icon']);
    }
}
