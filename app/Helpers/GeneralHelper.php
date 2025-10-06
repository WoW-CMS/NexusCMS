<?php

namespace App\Helpers;

class GeneralHelper
{
    /**
     * Calculates the estimated reading time of a text
     *
     * @param string $content The article content (HTML or plain text)
     * @param int $wordsPerMinute Average reading speed (default 200)
     * @return int Reading time in minutes
     */
    public static function readingTime(string $content, int $wordsPerMinute = 200): int
    {
        // Remove HTML tags
        $plainText = strip_tags($content);

        // Count words
        $wordCount = str_word_count($plainText);

        // Calculate minutes, rounded up
        $minutes = (int) ceil($wordCount / $wordsPerMinute);

        // Ensure at least 1 minute
        return $minutes > 0 ? $minutes : 1;
    }

    /**
     * Generates a share URL and Font Awesome icon for a social media platform
     *
     * @param string $social The social media platform (twitter, facebook, linkedin, pinterest, reddit)
     * @param string $url The URL to share
     * @param string $title The title to share
     * @return array ['url' => string, 'icon' => string]
     */
    public static function shareSocial(string $social, string $url, string $title): array
    {
        $socials = [
            'twitter' => [
                'url' => "https://x.com/intent/tweet?url={$url}&text={$title}",
                'icon' => 'fab fa-x',
            ],
            'facebook' => [
                'url' => "https://www.facebook.com/sharer/sharer.php?u={$url}&quote={$title}",
                'icon' => 'fab fa-facebook-f',
            ],
            'pinterest' => [
                'url' => "https://pinterest.com/pin/create/button/?url={$url}&description={$title}",
                'icon' => 'fab fa-pinterest-p',
            ],
            'reddit' => [
                'url' => "https://www.reddit.com/submit?url={$url}&title={$title}",
                'icon' => 'fab fa-reddit-alien',
            ],
        ];

        return $socials[$social] ?? ['url' => '#', 'icon' => ''];
    }
}
