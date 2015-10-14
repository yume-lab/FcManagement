<?php
return [

    /**
     * 基本設定
     */
    'App' => [
        'namespace' => 'App',
        'encoding' => 'UTF-8',
        'base' => false,
        'dir' => 'src',
        'webroot' => 'webroot',
        'wwwRoot' => WWW_ROOT,
        // 'baseUrl' => env('SCRIPT_NAME'),
        'fullBaseUrl' => false,
        'imageBaseUrl' => 'img/',
        'cssBaseUrl' => 'css/',
        'jsBaseUrl' => 'js/',
        'paths' => [
            'plugins' => [ROOT . DS . 'plugins' . DS],
            'templates' => [APP . 'Template' . DS],
            'locales' => [APP . 'Locale' . DS],
        ],
    ],

    /**
     * セキュリティハッシュ設定
     */
    'Security' => [
        'salt' => '__SALT__',
    ],

    /**
     * 出力されるCSSなどのキャッシュ対策
     */
    'Asset' => [
        'timestamp' => true
    ],

    /**
     * キャッシュ設定
     */
    'Cache' => [
        'default' => [
            'className' => 'File',
            'path' => CACHE,
        ],

        '_cake_core_' => [
            'className' => 'File',
            'prefix' => 'cake_core_',
            'path' => CACHE . 'persistent/',
            'serialize' => true,
            'duration' => '+2 minutes',
        ],

        '_cake_model_' => [
            'className' => 'File',
            'prefix' => 'cake_model_',
            'path' => CACHE . 'models/',
            'serialize' => true,
            'duration' => '+2 minutes',
        ],
    ],

    /**
     * エラーハンドリング
     */
    'Error' => [
        'errorLevel' => E_ALL & ~E_DEPRECATED,
        'exceptionRenderer' => 'Cake\Error\ExceptionRenderer',
        'skipLog' => [],
        'log' => true,
        'trace' => true,
    ],

    /**
     * ログ設定
     */
    'Log' => [
        'debug' => [
            'className' => 'Cake\Log\Engine\FileLog',
            'path' => LOGS,
            'file' => 'debug',
            'levels' => ['notice', 'info', 'debug'],
        ],
        'error' => [
            'className' => 'Cake\Log\Engine\FileLog',
            'path' => LOGS,
            'file' => 'error',
            'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],
        ],
    ],

    /**
     * セッション設定
     */
    'Session' => [
        'defaults' => 'php',
    ],

    /**
     * 固定値
     */
    'Define' => [
        'List' => [
            'Count' => 10
        ]
    ]
];
