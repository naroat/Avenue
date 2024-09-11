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

    public function auth($app = '')
    {
        $state = bin2hex(random_bytes(16));
        $this->client->setState($state);
        $this->client->setClientId(config('google.client_id'));
        $this->client->setScopes('email profile');
        $this->client->setRedirectUri('https://www.avenue.wang/api/v1/avenue/google/auth/notify');
        $this->client->setPrompt('consent');
        $url = $this->client->createAuthUrl();
        return $url;
    }

    public function notify($params)
    {
        Log::get()->info('========google notiify start ======');
        if (!isset($params['code'])) {
            Log::get()->info('error: no code');
            return false;
        }
//        $this->client->setAuthConfigFile();
//        $this->client->setAuthConfig();
        $this->client->setClientId(config('google.client_id'));
        $this->client->setScopes('email profile');
        $this->client->setClientSecret(config('google.client_secret'));
        $this->client->setRedirectUri('https://www.avenue.wang/api/v1/avenue/google/auth/notify');
        Log::get()->info('参数：' . json_encode($params));
        $res = $this->client->fetchAccessTokenWithAuthCode($params['code']);
        Log::get()->info('auth code:' . json_encode($res));

        $accessToken = $this->client->getAccessToken();

        Log::get()->info('access_token:' . $accessToken ?? '');

        Log::get()->info('======== google notiify end ======');
    }
}