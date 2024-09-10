<?php

declare(strict_types=1);

use Hyperf\Cache\Driver\RedisDriver;
use Hyperf\Codec\Packer\PhpSerializerPacker;

return [
    'application_name' => env('GOOGLE_APPLICATION_NAME', ''),
    'client_id' => env('GOOGLE_CLIENT_ID', ''),
    'client_secret' => env('GOOGLE_CLIENT_SECRET', ''),
];
