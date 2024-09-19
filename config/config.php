<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */
use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Log\LogLevel;

use function Hyperf\Support\env;

return [
    'app_name' => env('APP_NAME', 'mineAdmin'),
    'app_env' => env('APP_ENV', 'dev'),
    'scan_cacheable' => env('SCAN_CACHEABLE', false),
    StdoutLoggerInterface::class => [
        'log_level' => [
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            //            LogLevel::DEBUG,
            LogLevel::EMERGENCY,
            LogLevel::ERROR,
            LogLevel::INFO,
            LogLevel::NOTICE,
            LogLevel::WARNING,
        ],
    ],
    # curl proxy
    'curl_proxy' => [
        'enable' => env('CURL_PROXY_ENABLE', 'false'),
        'host' => env('CURL_PROXY_HOST', ''),
        'port' => env('CURL_PROXY_POST', ''),
        'type' => ['http' => CURLPROXY_HTTP, 'socket4' => CURLPROXY_SOCKS4, 'socket5' => CURLPROXY_SOCKS5][env('CURL_PROXY_TYPE', 'http')],
    ]
];
