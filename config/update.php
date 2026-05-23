<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Auto-Update System
    |--------------------------------------------------------------------------
    |
    | Configuration for the NexusCMS built-in updater.
    | The updater fetches releases from GitHub (direct API or via proxy)
    | and applies them either through git or zip extraction.
    |
    */

    'enabled' => env('UPDATE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Source Repository
    |--------------------------------------------------------------------------
    |
    | The GitHub owner and repository name to check for updates.
    | Change these if you are running a fork or a private mirror.
    |
    */
    'repository' => [
        'owner' => env('UPDATE_REPO_OWNER', 'wow-cms'),
        'name'  => env('UPDATE_REPO_NAME', 'nexuscms'),
    ],

    /*
    |--------------------------------------------------------------------------
    | GitHub Token
    |--------------------------------------------------------------------------
    |
    | Optional GitHub personal access token. Required for private repositories
    | or to avoid GitHub API rate limits (60 requests/hour anonymous).
    |
    */
    'github_token' => env('GITHUB_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Release Channel
    |--------------------------------------------------------------------------
    |
    | Controls which releases are considered when checking for updates:
    |   "stable" — only non-prerelease tagged releases  (recommended)
    |   "beta"   — includes pre-releases flagged as beta
    |   "any"    — includes all published releases (including alpha/RC)
    |
    */
    'channel' => env('UPDATE_CHANNEL', 'any'),

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode
    |--------------------------------------------------------------------------
    |
    | If true, the updater will call `artisan down` before applying the update
    | and `artisan up` afterwards, regardless of success or failure.
    |
    */
    'maintenance_mode' => env('UPDATE_MAINTENANCE_MODE', true),

    /*
    |--------------------------------------------------------------------------
    | Paths Excluded from ZIP Updates
    |--------------------------------------------------------------------------
    |
    | When applying a ZIP-based update, these paths (relative to base_path)
    | will be skipped to avoid overwriting local configuration and data.
    |
    */
    'skip_paths' => [
        '.env',
        '.env.production',
        'vendor',
        'node_modules',
        'storage',
        'public/uploads',
        'public/storage',
    ],

];
