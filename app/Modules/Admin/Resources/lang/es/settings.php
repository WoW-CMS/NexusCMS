<?php

return [
    'menu' => [
        'general' => 'General',
        'email' => 'Email',
        'payment' => 'Pago',
        'security' => 'Seguridad',
        'appearance' => 'Apariencia',
        'seo' => 'SEO',
        'api' => 'API',
        'maintenance' => 'Mantenimiento',
        'localization' => 'Localización',
        'advanced' => 'Avanzado',
    ],

    'localization' => [
        'title' => 'Configuración de localización',
        'description' => 'Configura las preferencias de idioma, hora y moneda',
        'default_locale' => [
            'label' => 'Idioma predeterminado',
            'help'  => 'El idioma predeterminado de la aplicación.',
        ],
        'fallback_locale' => [
            'label' => 'Idioma de respaldo',
            'help'  => 'Idioma utilizado cuando falta una traducción.',
        ],
        'timezone' => [
            'label' => 'Zona horaria',
            'help'  => 'Configuración de la zona horaria a nivel del sistema.',
        ],
        'date_format' => [
            'label' => 'Formato de fecha',
            'help'  => 'Formato de visualización para las fechas.',
        ],
        'time_format' => [
            'label' => 'Formato de hora',
            'help'  => 'Formato de visualización para las horas.',
        ],
        'multilingual' => [
            'section_title'       => 'Contenido multiidioma',
            'section_description' => 'Activa y configura el soporte multiidioma para noticias y contenido.',
            'enabled_label'       => 'Activar soporte multiidioma',
            'enabled_help'        => 'Cuando está activado, los editores pueden introducir traducciones para cada idioma configurado.',
            'locales_label'       => 'Idiomas disponibles',
            'locales_help'        => 'Idiomas disponibles en el editor de contenido. El idioma predeterminado siempre se incluye.',
            'add_locale'          => 'Añadir código de idioma (ej. fr)',
            'locale_placeholder'  => 'ej. fr, de, pt',
        ],
    ],

    'advanced' => [
        'title'       => 'Configuración avanzada',
        'description' => 'Seeders de base de datos, gestión de caché y otras operaciones avanzadas.',

        // Seeders
        'seeders_title'       => 'Seeders de base de datos',
        'seeders_description' => 'Vuelve a ejecutar los seeders para sincronizar los datos por defecto. Los seeders usan firstOrCreate — no sobreescribirán registros existentes.',
        'seeder_roles_title'   => 'Roles y permisos',
        'seeder_roles_desc'    => 'Sincroniza todos los roles y permisos con las últimas definiciones.',
        'seeder_forums_title'  => 'Foros',
        'seeder_forums_desc'   => 'Crea las categorías y secciones de foro por defecto.',
        'seeder_donations_title' => 'Planes de donación',
        'seeder_donations_desc'  => 'Crea los planes de donación/tienda por defecto.',
        'seeder_all_title'    => 'Ejecutar todos los seeders',
        'seeder_all_desc'     => 'Ejecuta el DatabaseSeeder completo (todos los anteriores).',
        'run'                 => 'Ejecutar',
        'run_all'             => 'Ejecutar todo',
        'confirm_run'         => '¿Estás seguro de que quieres ejecutar este seeder?',
        'confirm_run_all'     => '¿Estás seguro de que quieres ejecutar TODOS los seeders?',
        'seeder_success'      => 'Seeder ejecutado correctamente.',
        'seeder_failed'       => 'El seeder falló: :error',
        'seeder_invalid'      => 'Seeder seleccionado no válido.',

        // Cache
        'cache_title'       => 'Gestión de caché',
        'cache_description' => 'Limpia las cachés compiladas. Úsalo después de cambios de configuración o código.',
        'cache_config_title' => 'Caché de configuración',
        'cache_config_desc'  => 'Limpia la caché de configuración compilada.',
        'cache_routes_title' => 'Caché de rutas',
        'cache_routes_desc'  => 'Limpia la caché de rutas compiladas.',
        'cache_views_title'  => 'Caché de vistas',
        'cache_views_desc'   => 'Limpia los archivos Blade compilados.',
        'cache_app_title'    => 'Caché de la aplicación',
        'cache_app_desc'     => 'Limpia la caché general de la aplicación.',
        'cache_all_title'    => 'Limpiar todo',
        'cache_all_desc'     => 'Ejecuta optimize:clear — limpia todas las cachés a la vez.',
        'clear'              => 'Limpiar',
        'confirm_cache'      => '¿Estás seguro de que quieres limpiar esta caché?',
        'cache_success'      => 'Caché limpiada correctamente.',
        'cache_failed'       => 'Error al limpiar la caché: :error',
        'cache_invalid'      => 'Tipo de caché seleccionado no válido.',
    ],

    'maintenance' => [
        'title' => 'Configuración de mantenimiento',
        'description' => 'Configura el modo de mantenimiento y los ajustes de acceso',
        'enabled' => 'Habilitar modo de mantenimiento',
        'message' => [
            'label' => 'Mensaje de mantenimiento',
            'help'  => 'Este mensaje se mostrará a los usuarios cuando el modo de mantenimiento esté activo.',
        ],
    ],

    'email' => [
        'title' => 'Configuración de correo electrónico',
        'description' => 'Configura los ajustes SMTP para el envío de correos.',
        'smtp_host' => [
            'label' => 'Servidor SMTP',
            'help'  => 'Nombre de host o dirección IP del servidor SMTP.',
        ],
        'smtp_port' => [
            'label' => 'Puerto SMTP',
            'help'  => 'Número de puerto del servidor SMTP.',
        ],
        'smtp_username' => 'Usuario SMTP',
        'smtp_password' => 'Contraseña SMTP',
        'smtp_encryption' => 'Cifrado',
        'mail_from_name' => 'Nombre del remitente',
        'send_test' => 'Enviar correo de prueba',
    ],

    'general' => [
        'title' => 'Configuración general',
        'description' => 'Configura la información básica y los ajustes del sitio',
        'site_name' => 'Nombre del sitio',
        'site_url' => 'URL del sitio',
        'site_description' => 'Descripción del sitio',
        'admin_email' => 'Correo del administrador',
        'allow_registration' => 'Permitir el registro de nuevos usuarios',
    ],

    'security' => [
        'title' => 'Configuración de seguridad',
        'description' => 'Configura la autenticación, las políticas de contraseñas y la protección de cuentas',
        'password_policies' => [
            'title' => 'Políticas de contraseñas',
            'min_length' => 'Longitud mínima de la contraseña',
            'require_uppercase' => 'Requerir letras mayúsculas',
            'require_numbers' => 'Requerir números',
            'require_special' => 'Requerir caracteres especiales',
        ],
        'account_protection' => [
            'title' => 'Protección de la cuenta',
            'max_login_attempts' => 'Máximo de intentos de inicio de sesión',
            'lockout_duration' => 'Duración del bloqueo (minutos)',
        ],
        'session' => [
            'title' => 'Sesión y autenticación',
            'timeout' => 'Tiempo de espera de la sesión (minutos)',
            'force_https' => 'Forzar HTTPS',
            'enable_2fa' => 'Habilitar 2FA',
            'require_email_verification' => 'Requerir verificación de correo electrónico',
        ],
    ],
];
