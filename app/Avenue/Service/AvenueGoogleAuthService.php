<?php
declare(strict_types=1);

namespace App\Avenue\Service;

use App\Package\Log;
use App\Package\Utils;
use Hyperf\Di\Annotation\Inject;
use Mine\Abstracts\AbstractService;
use Google;

class AvenueGoogleAuthService extends AbstractService
{
    #[Inject]
    public Google\Client $client;

    public function auth($app)
    {
        $this->client->setClientId(config('google.client_id'));
        $this->client->setScopes('email profile');
        $this->client->setRedirectUri('http://localhost:9501/v1/avenue/google/auth/notify');
        $this->client->createAuthUrl();
    }

    public function notify($params)
    {
        Log::get()->info('========google notiify start ======');
        if (isset($params['code']) && $params['code'] == '') {
            throw new \Exception("param error");
        }
        $this->client->setClientId(config('google.client_id'));
        $this->client->setScopes('email profile');
        $this->client->setClientSecret(config('google.client_secret'));
        Log::get()->info(json_encode($params));
        $res = $this->client->fetchAccessTokenWithAuthCode($params['code']);
        Log::get()->info(json_encode($res));

        $accessToken = $this->client->getAccessToken();

        Log::get()->info($accessToken ?? '');

        Log::get()->info('======== google notiify end ======');
    }
}