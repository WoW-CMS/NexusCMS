@extends('admin::settings.index')

@section('settings_content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">{{ ucfirst($view) }} Settings</h2>
        <p class="text-sm text-gray-600 mt-1">Configure search engine optimization and social sharing preferences</p>
    </div>
    <div class="p-6 space-y-8">
        <!-- Global Meta Tags -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Global Meta Tags</h3>
            <div class="space-y-4">
                <div>
                    <label for="seo_meta_title" class="block text-sm font-semibold text-gray-700 mb-2">Default Meta Title</label>
                    <input type="text" id="seo_meta_title" name="seo_meta_title" value="{{ $settings['seo_meta_title'] ?? '' }}" placeholder="{{ $settings['site_name'] ?? 'NexusCMS' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Used as the default title tag if no specific title is set for a page.</p>
                </div>
                
                <div>
                    <label for="seo_meta_description" class="block text-sm font-semibold text-gray-700 mb-2">Default Meta Description</label>
                    <textarea id="seo_meta_description" name="seo_meta_description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $settings['seo_meta_description'] ?? '' }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">A brief summary of your site (recommended 150-160 characters).</p>
                </div>

                <div>
                    <label for="seo_meta_keywords" class="block text-sm font-semibold text-gray-700 mb-2">Meta Keywords</label>
                    <input type="text" id="seo_meta_keywords" name="seo_meta_keywords" value="{{ $settings['seo_meta_keywords'] ?? '' }}" placeholder="wow, server, mmorpg, game" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Comma-separated list of keywords.</p>
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Social Media (Open Graph & Twitter)</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label for="seo_og_image" class="block text-sm font-semibold text-gray-700 mb-2">Default Social Image URL</label>
                    <input type="text" id="seo_og_image" name="seo_og_image" value="{{ $settings['seo_og_image'] ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Absolute URL to an image used when sharing on social media.</p>
                </div>

                <div>
                    <label for="seo_twitter_handle" class="block text-sm font-semibold text-gray-700 mb-2">Twitter Handle</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">@</span>
                        <input type="text" id="seo_twitter_handle" name="seo_twitter_handle" value="{{ $settings['seo_twitter_handle'] ?? '' }}" class="w-full pl-8 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Your site's Twitter username.</p>
                </div>
            </div>
        </div>

        <!-- Analytics & Tracking -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Analytics & Tracking</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label for="seo_google_analytics" class="block text-sm font-semibold text-gray-700 mb-2">Google Analytics ID</label>
                    <input type="text" id="seo_google_analytics" name="seo_google_analytics" value="{{ $settings['seo_google_analytics'] ?? '' }}" placeholder="G-XXXXXXXXXX" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Your Google Analytics 4 Measurement ID.</p>
                </div>
                <div>
                    <label for="seo_gtm_id" class="block text-sm font-semibold text-gray-700 mb-2">Google Tag Manager ID</label>
                    <input type="text" id="seo_gtm_id" name="seo_gtm_id" value="{{ $settings['seo_gtm_id'] ?? '' }}" placeholder="GTM-XXXXXX" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Custom Scripts -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Custom Scripts</h3>
            <div class="space-y-4">
                <div>
                    <label for="seo_custom_head" class="block text-sm font-semibold text-gray-700 mb-2">Header Scripts</label>
                    <textarea id="seo_custom_head" name="seo_custom_head" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-xs">{{ $settings['seo_custom_head'] ?? '' }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Code to be inserted before the closing &lt;/head&gt; tag.</p>
                </div>
                <div>
                    <label for="seo_custom_body" class="block text-sm font-semibold text-gray-700 mb-2">Body Scripts</label>
                    <textarea id="seo_custom_body" name="seo_custom_body" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-xs">{{ $settings['seo_custom_body'] ?? '' }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Code to be inserted before the closing &lt;/body&gt; tag.</p>
                </div>
            </div>
        </div>

        <!-- Indexing -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Indexing</h3>
            <div class="flex items-center gap-2">
                <input type="hidden" name="seo_allow_indexing" value="0">
                <input type="checkbox" id="seo_allow_indexing" name="seo_allow_indexing" value="1" {{ ($settings['seo_allow_indexing'] ?? '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <label for="seo_allow_indexing" class="text-sm text-gray-700">Allow search engines to index this site</label>
            </div>
            <p class="mt-1 text-xs text-gray-500 ml-6">Uncheck this to add &lt;meta name="robots" content="noindex, nofollow"&gt; to your pages.</p>
        </div>
    </div>
</div>
@endsection
