<?php

return [
    'menu' => [
        'general' => 'General',
        'email' => 'Email',
        'payment' => 'Payment',
        'security' => 'Security',
        'appearance' => 'Appearance',
        'seo' => 'SEO',
        'api' => 'API',
        'maintenance' => 'Maintenance',
        'localization' => 'Localization',
        'advanced' => 'Advanced',
    ],

    'localization' => [
        'title' => 'Localization Settings',
        'description' => 'Configure language, time, and currency preferences',
        'default_locale' => [
            'label' => 'Default Locale',
            'help'  => 'The default language for the application.',
        ],
        'fallback_locale' => [
            'label' => 'Fallback Locale',
            'help'  => 'Language used when a translation is missing.',
        ],
        'timezone' => [
            'label' => 'Timezone',
            'help'  => 'System-wide timezone setting.',
        ],
        'date_format' => [
            'label' => 'Date Format',
            'help'  => 'Display format for dates.',
        ],
        'time_format' => [
            'label' => 'Time Format',
            'help'  => 'Display format for times.',
        ],
        'multilingual' => [
            'section_title'       => 'Multilingual Content',
            'section_description' => 'Enable and configure multilingual support for news and content.',
            'enabled_label'       => 'Enable Multilingual Support',
            'enabled_help'        => 'When enabled, content editors can enter translations for each configured language.',
            'locales_label'       => 'Available Languages',
            'locales_help'        => 'Languages available in the content editor. The default locale is always included.',
            'add_locale'          => 'Add language code (e.g. fr)',
            'locale_placeholder'  => 'e.g. fr, de, pt',
        ],
    ],

    'advanced' => [
        'title'       => 'Advanced Settings',
        'description' => 'Database seeders, cache management, and other advanced operations.',

        // Seeders
        'seeders_title'       => 'Database Seeders',
        'seeders_description' => 'Re-run seeders to synchronise default data. Seeders use firstOrCreate — they will not overwrite existing records.',
        'seeder_roles_title'   => 'Roles & Permissions',
        'seeder_roles_desc'    => 'Sync all roles and permissions to the latest definitions.',
        'seeder_forums_title'  => 'Forums',
        'seeder_forums_desc'   => 'Seed default forum categories and sections.',
        'seeder_donations_title' => 'Donation Plans',
        'seeder_donations_desc'  => 'Seed default donation/store plans.',
        'seeder_all_title'    => 'Run All Seeders',
        'seeder_all_desc'     => 'Run the full DatabaseSeeder (all of the above).',
        'run'                 => 'Run',
        'run_all'             => 'Run All',
        'confirm_run'         => 'Are you sure you want to run this seeder?',
        'confirm_run_all'     => 'Are you sure you want to run ALL seeders?',
        'seeder_success'      => 'Seeder ran successfully.',
        'seeder_failed'       => 'Seeder failed: :error',
        'seeder_invalid'      => 'Invalid seeder selected.',

        // Cache
        'cache_title'       => 'Cache Management',
        'cache_description' => 'Clear compiled caches. Use this after configuration or code changes.',
        'cache_config_title' => 'Config Cache',
        'cache_config_desc'  => 'Clear the compiled configuration cache.',
        'cache_routes_title' => 'Routes Cache',
        'cache_routes_desc'  => 'Clear the compiled routes cache.',
        'cache_views_title'  => 'Views Cache',
        'cache_views_desc'   => 'Clear compiled Blade view files.',
        'cache_app_title'    => 'Application Cache',
        'cache_app_desc'     => 'Clear the general application cache.',
        'cache_all_title'    => 'Clear All Caches',
        'cache_all_desc'     => 'Run optimize:clear — clears all caches at once.',
        'clear'              => 'Clear',
        'confirm_cache'      => 'Are you sure you want to clear this cache?',
        'cache_success'      => 'Cache cleared successfully.',
        'cache_failed'       => 'Cache clear failed: :error',
        'cache_invalid'      => 'Invalid cache type selected.',
    ],

    'maintenance' => [
        'title' => 'Maintenance Settings',
        'description' => 'Configure maintenance mode and access settings',
        'enabled' => 'Enable Maintenance Mode',
        'message' => [
            'label' => 'Maintenance Message',
            'help'  => 'This message will be displayed to users when maintenance mode is active.',
        ],
    ],

    'email' => [
        'title' => 'Email Settings',
        'description' => 'Configure SMTP settings for outgoing emails.',
        'smtp_host' => [
            'label' => 'SMTP Host',
            'help'  => 'Hostname or IP address of the SMTP server.',
        ],
        'smtp_port' => [
            'label' => 'SMTP Port',
            'help'  => 'Port number for the SMTP server.',
        ],
        'smtp_username' => 'SMTP Username',
        'smtp_password' => 'SMTP Password',
        'smtp_encryption' => 'Encryption',
        'mail_from_name' => 'From Name',
        'send_test' => 'Send Test Email',
    ],

    'general' => [
        'title' => 'General Settings',
        'description' => 'Configure basic site information and settings',
        'site_name' => 'Site Name',
        'site_url' => 'Site URL',
        'site_description' => 'Site Description',
        'admin_email' => 'Admin Email',
        'allow_registration' => 'Allow new user registrations',
    ],
    'security' => [
        'title' => 'Security Settings',
        'description' => 'Configure authentication, password policies, and account protection settings',
        'password_policies' => [
            'title' => 'Password Policies',
            'min_length' => 'Minimum Password Length',
            'require_uppercase' => 'Require Uppercase Letters',
            'require_numbers' => 'Require Numbers',
            'require_special' => 'Require Special Characters',
        ],
        'account_protection' => [
            'title' => 'Account Protection',
            'max_login_attempts' => 'Max Login Attempts',
            'lockout_duration' => 'Lockout Duration (minutes)',
        ],
        'session' => [
            'title' => 'Session and Authentication',
            'timeout' => 'Session Timeout (minutes)',
            'force_https' => 'Force HTTPS',
            'enable_2fa' => 'Enable 2FA',
            'require_email_verification' => 'Require Email Verification',
        ],
    ],
];
