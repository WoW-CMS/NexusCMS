<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Helpers\GeneralHelper;

class GeneralHelperTest extends TestCase
{
    public function test_reading_time_calculates_correctly(): void
    {
        $text = str_repeat('word ', 400);

        $minutes = GeneralHelper::readingTime($text);

        $this->assertEquals(2, $minutes);
    }

    public function test_reading_time_returns_at_least_one(): void
    {
        $this->assertEquals(1, GeneralHelper::readingTime(''));
    }

    public function test_share_social_twitter(): void
    {
        $result = GeneralHelper::shareSocial(
            'twitter',
            'https://example.com',
            'Test Title'
        );

        $this->assertStringContainsString('x.com/intent/tweet', $result['url']);
        $this->assertEquals('fab fa-x', $result['icon']);
    }

    public function test_share_social_facebook(): void
    {
        $result = GeneralHelper::shareSocial(
            'facebook',
            'https://example.com',
            'Test Title'
        );

        $this->assertStringContainsString('facebook.com/sharer/sharer.php', $result['url']);
        $this->assertEquals('fab fa-facebook-f', $result['icon']);
    }

    public function test_share_social_pinterest(): void
    {
        $result = GeneralHelper::shareSocial(
            'pinterest',
            'https://example.com',
            'Test Title'
        );

        $this->assertStringContainsString('pinterest.com/pin/create/button', $result['url']);
        $this->assertEquals('fab fa-pinterest-p', $result['icon']);
    }

    public function test_share_social_reddit(): void
    {
        $result = GeneralHelper::shareSocial(
            'reddit',
            'https://example.com',
            'Test Title'
        );

        $this->assertStringContainsString('www.reddit.com/submit', $result['url']);
        $this->assertEquals('fab fa-reddit-alien', $result['icon']);
    }
}