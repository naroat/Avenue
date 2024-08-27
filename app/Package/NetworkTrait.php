<?php


namespace App\Package;

use Hyperf\HttpServer\Contract\RequestInterface;
//use Hyperf\Utils\ApplicationContext;

class NetworkTrait
{
    /**
     * 获取客户端ip
     *
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function getClientIp() {
        $request = make(RequestInterface::class);
        $headers = $request->getHeaders();
        if(isset($headers['x-forwarded-for'][0]) && !empty($headers['x-forwarded-for'][0])) {
            return $headers['x-forwarded-for'][0];
        } elseif (isset($headers['x-real-ip'][0]) && !empty($headers['x-real-ip'][0])) {
            return $headers['x-real-ip'][0];
        }
        $serverParams = $request->getServerParams();
        return $serverParams['remote_addr'] ?? '';
    }
}