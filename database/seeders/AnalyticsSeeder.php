<?php

namespace Database\Seeders;

use App\Models\AnalyticsSession;
use App\Models\AnalyticsPageView;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            ['url' => '/', 'title' => 'Home'],
            ['url' => '/blog', 'title' => 'Blog'],
            ['url' => '/products', 'title' => 'Products'],
            ['url' => '/about', 'title' => 'About Us'],
            ['url' => '/contact', 'title' => 'Contact'],
            ['url' => '/blog/getting-started', 'title' => 'Getting Started Guide'],
            ['url' => '/products/premium', 'title' => 'Premium Plan'],
            ['url' => '/news', 'title' => 'News'],
        ];

        $countries = [
            'United States',
            'Spain',
            'Mexico',
            'Argentina',
            'Colombia',
            'Chile',
        ];

        // Create 100 sample sessions with page views
        for ($i = 0; $i < 100; $i++) {
            $session = AnalyticsSession::create([
                'visitor_id' => 'visitor_' . generateUniqueId()(),
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
                'country' => fake()->randomElement($countries),
                'city' => fake()->city(),
                'duration_seconds' => fake()->numberBetween(60, 1800),
                'page_views' => fake()->numberBetween(1, 10),
                'is_bot' => fake()->boolean(5),
                'created_at' => Carbon::now()->subDays(fake()->numberBetween(0, 30)),
                'updated_at' => Carbon::now()->subDays(fake()->numberBetween(0, 30)),
            ]);

            // Create 1-5 page views per session
            $pageCount = fake()->numberBetween(1, 5);
            for ($j = 0; $j < $pageCount; $j++) {
                $page = fake()->randomElement($pages);
                AnalyticsPageView::create([
                    'session_id' => $session->id,
                    'page_url' => $page['url'],
                    'page_title' => $page['title'],
                    'referrer' => fake()->randomElement([null, 'google.com', 'facebook.com', 'twitter.com']),
                    'time_on_page' => fake()->numberBetween(10, 600),
                    'bounced' => $pageCount === 1 ? fake()->boolean(40) : false,
                    'created_at' => Carbon::now()->subDays(fake()->numberBetween(0, 30)),
                    'updated_at' => Carbon::now()->subDays(fake()->numberBetween(0, 30)),
                ]);
            }
        }
    }
}

// Helper function to generate unique IDs
function generateUniqueId(): callable
{
    static $counter = 0;
    return fn() => ++$counter;
}
