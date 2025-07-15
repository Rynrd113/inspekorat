<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Performance Monitoring Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file contains settings for performance monitoring
    | and optimization features in the application.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Performance Thresholds
    |--------------------------------------------------------------------------
    |
    | Define performance thresholds for alerting. When these thresholds are
    | exceeded, alerts will be generated.
    |
    */
    'thresholds' => [
        'execution_time' => env('PERFORMANCE_EXECUTION_TIME_THRESHOLD', 1000), // milliseconds
        'memory_usage' => env('PERFORMANCE_MEMORY_THRESHOLD', 50), // MB
        'query_count' => env('PERFORMANCE_QUERY_COUNT_THRESHOLD', 20),
        'content_length' => env('PERFORMANCE_CONTENT_LENGTH_THRESHOLD', 1048576), // 1MB
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Storage
    |--------------------------------------------------------------------------
    |
    | Configure whether to store performance data in the database for
    | analysis and reporting.
    |
    */
    'store_data' => env('PERFORMANCE_STORE_DATA', false),
    
    /*
    |--------------------------------------------------------------------------
    | Data Retention
    |--------------------------------------------------------------------------
    |
    | Configure how long to keep performance data in the database.
    | Value is in days.
    |
    */
    'data_retention_days' => env('PERFORMANCE_DATA_RETENTION_DAYS', 30),

    /*
    |--------------------------------------------------------------------------
    | Monitoring Channels
    |--------------------------------------------------------------------------
    |
    | Configure which channels to use for performance monitoring alerts.
    |
    */
    'channels' => [
        'log' => true,
        'email' => env('PERFORMANCE_EMAIL_ALERTS', false),
        'slack' => env('PERFORMANCE_SLACK_ALERTS', false),
        'database' => env('PERFORMANCE_DATABASE_ALERTS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Eager Loading Configuration
    |--------------------------------------------------------------------------
    |
    | Configure eager loading optimization settings.
    |
    */
    'eager_loading' => [
        'enabled' => env('EAGER_LOADING_ENABLED', true),
        'batch_size' => env('EAGER_LOADING_BATCH_SIZE', 100),
        'max_depth' => env('EAGER_LOADING_MAX_DEPTH', 3),
        'cache_duration' => env('EAGER_LOADING_CACHE_DURATION', 3600), // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Asset Optimization Configuration
    |--------------------------------------------------------------------------
    |
    | Configure asset optimization settings including lazy loading,
    | image optimization, and caching.
    |
    */
    'assets' => [
        'lazy_loading' => [
            'enabled' => env('ASSET_LAZY_LOADING_ENABLED', true),
            'threshold' => env('ASSET_LAZY_LOADING_THRESHOLD', 0.1),
            'root_margin' => env('ASSET_LAZY_LOADING_ROOT_MARGIN', '50px'),
        ],
        
        'image_optimization' => [
            'enabled' => env('ASSET_IMAGE_OPTIMIZATION_ENABLED', true),
            'webp_support' => env('ASSET_WEBP_SUPPORT', true),
            'quality' => env('ASSET_IMAGE_QUALITY', 85),
            'max_width' => env('ASSET_IMAGE_MAX_WIDTH', 1920),
            'max_height' => env('ASSET_IMAGE_MAX_HEIGHT', 1080),
        ],
        
        'caching' => [
            'enabled' => env('ASSET_CACHING_ENABLED', true),
            'cache_duration' => env('ASSET_CACHE_DURATION', 86400), // 24 hours
            'service_worker' => env('ASSET_SERVICE_WORKER_ENABLED', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Query Optimization
    |--------------------------------------------------------------------------
    |
    | Configure database query optimization settings.
    |
    */
    'database' => [
        'query_log' => env('DB_QUERY_LOG_ENABLED', config('app.debug')),
        'slow_query_threshold' => env('DB_SLOW_QUERY_THRESHOLD', 1000), // milliseconds
        'index_hints' => env('DB_INDEX_HINTS_ENABLED', true),
        'connection_pool' => env('DB_CONNECTION_POOL_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching Configuration
    |--------------------------------------------------------------------------
    |
    | Configure caching optimization settings.
    |
    */
    'caching' => [
        'enabled' => env('CACHE_OPTIMIZATION_ENABLED', true),
        'default_ttl' => env('CACHE_DEFAULT_TTL', 3600), // 1 hour
        'tags_enabled' => env('CACHE_TAGS_ENABLED', true),
        'compression' => env('CACHE_COMPRESSION_ENABLED', true),
        'serialization' => env('CACHE_SERIALIZATION', 'serialize'), // serialize, json, igbinary
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Optimization
    |--------------------------------------------------------------------------
    |
    | Configure session optimization settings.
    |
    */
    'session' => [
        'optimization_enabled' => env('SESSION_OPTIMIZATION_ENABLED', true),
        'gc_probability' => env('SESSION_GC_PROBABILITY', 1),
        'gc_divisor' => env('SESSION_GC_DIVISOR', 100),
        'cookie_secure' => env('SESSION_COOKIE_SECURE', false),
        'cookie_httponly' => env('SESSION_COOKIE_HTTPONLY', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Response Optimization
    |--------------------------------------------------------------------------
    |
    | Configure response optimization settings including compression
    | and minification.
    |
    */
    'response' => [
        'compression' => [
            'enabled' => env('RESPONSE_COMPRESSION_ENABLED', true),
            'level' => env('RESPONSE_COMPRESSION_LEVEL', 6),
            'threshold' => env('RESPONSE_COMPRESSION_THRESHOLD', 1024), // bytes
        ],
        
        'minification' => [
            'html' => env('RESPONSE_MINIFY_HTML', true),
            'css' => env('RESPONSE_MINIFY_CSS', true),
            'js' => env('RESPONSE_MINIFY_JS', true),
        ],
        
        'headers' => [
            'etag' => env('RESPONSE_ETAG_ENABLED', true),
            'last_modified' => env('RESPONSE_LAST_MODIFIED_ENABLED', true),
            'cache_control' => env('RESPONSE_CACHE_CONTROL', 'public, max-age=3600'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Monitoring APIs
    |--------------------------------------------------------------------------
    |
    | Configure external performance monitoring services.
    |
    */
    'monitoring' => [
        'new_relic' => [
            'enabled' => env('NEW_RELIC_ENABLED', false),
            'license_key' => env('NEW_RELIC_LICENSE_KEY'),
            'app_name' => env('NEW_RELIC_APP_NAME', config('app.name')),
        ],
        
        'datadog' => [
            'enabled' => env('DATADOG_ENABLED', false),
            'api_key' => env('DATADOG_API_KEY'),
            'app_key' => env('DATADOG_APP_KEY'),
        ],
        
        'sentry' => [
            'enabled' => env('SENTRY_ENABLED', false),
            'dsn' => env('SENTRY_LARAVEL_DSN'),
            'performance_monitoring' => env('SENTRY_PERFORMANCE_MONITORING', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Development Tools
    |--------------------------------------------------------------------------
    |
    | Configure development tools for performance debugging.
    |
    */
    'development' => [
        'debug_bar' => env('PERFORMANCE_DEBUG_BAR', config('app.debug')),
        'clockwork' => env('PERFORMANCE_CLOCKWORK', false),
        'telescope' => env('PERFORMANCE_TELESCOPE', false),
        'query_detector' => env('PERFORMANCE_QUERY_DETECTOR', config('app.debug')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Excluded Routes
    |--------------------------------------------------------------------------
    |
    | Routes to exclude from performance monitoring.
    |
    */
    'excluded_routes' => [
        'telescope/*',
        'horizon/*',
        'nova/*',
        'admin/performance/*',
        'api/health',
        'api/ping',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sampling Rate
    |--------------------------------------------------------------------------
    |
    | Configure sampling rate for performance monitoring to reduce overhead.
    | Value between 0.0 and 1.0 (1.0 = monitor all requests).
    |
    */
    'sampling_rate' => env('PERFORMANCE_SAMPLING_RATE', 1.0),
];
