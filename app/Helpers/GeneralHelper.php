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
}
