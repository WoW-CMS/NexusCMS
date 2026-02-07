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
    ],

    'advanced' => [
        'title' => 'Advanced Settings',
        'description' => 'Configure advanced settings',
        'under_construction' => 'This section is under construction.',
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
