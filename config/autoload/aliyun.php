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
use Hyperf\Amqp\IO\IOFactory;

use function Hyperf\Support\env;

return [
    /**
     * Oss
     */
    'oss' => [
        'access_key_id' => env('OSS_ACCESS_KEY_ID', ''),
        'access_key_secret' => env('OSS_ACCESS_KEY_SECRET', ''),
        'endpoint' => env('OSS_ENDPOINT', ''),
        'bucket' => env('OSS_BUCKET', ''),
        'sub_dir' => env('OSS_SUB_DIR', 'default'),
        'bucket_domain' => env('OSS_BUCKET_DOMAIN', ''),
    ]
];
