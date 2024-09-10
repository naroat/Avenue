<?php
declare(strict_types=1);

namespace App\Avenue\Service;

use App\Package\Utils;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\Logger;
use Mine\Abstracts\AbstractService;
use Google;

class AvenueGoogleAuthService extends AbstractService
{
    #[Inject]
    public Google\Client $client;

    #[Inject]
    public Logger $logger;
    public function auth($app)
    {
        $this->client->setClientId(config('google.client_id'));
        $this->client->setScopes('email profile');
        $this->client->setRedirectUri('http://localhost:9501/v1/avenue/google/auth/notify');
        $this->client->createAuthUrl();
    }

    public function notify($params)
    {
        if (isset($params['code']) && $params['code'] == '') {
            throw new \Exception("param error");
        }
        $this->client->fetchAccessTokenWithAuthCode($params['code']);
        $accessToken = $this->client->getAccessToken();
    }
}