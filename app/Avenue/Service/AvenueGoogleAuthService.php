<?php
declare(strict_types=1);

namespace App\Avenue\Service;

use App\Avenue\Model\AvenueUser;
use App\Avenue\Model\AvenueUserToken;
use App\Package\Log;
use App\Package\Utils;
use App\Package\Verify;
use Hyperf\DbConnection\Annotation\Transactional;
use Hyperf\Di\Annotation\Inject;
use Mine\Abstracts\AbstractService;
use Google;

class AvenueGoogleAuthService extends AbstractService
{
    #[Inject]
    public Google\Client $client;

    protected \Xmo\JWTAuth\JWT $jwt;

    const TOKENINFO_URL = 'https://www.googleapis.com/oauth2/v3/tokeninfo';

    public function authUrl()
    {
        $state = bin2hex(random_bytes(16));
        $this->client->setState($state);
        $this->client->setClientId(config('google.client_id'));
        $this->client->setScopes('email profile');
        $this->client->setRedirectUri(config('google.grant_domain') . '/api/v1/avenue/google/auth/notify');
        $this->client->setPrompt('consent');
        $url = $this->client->createAuthUrl();
        return $url;
    }

    #[Transactional]
    public function notify($params)
    {
        Log::get()->info('========google notify start ======');
        Log::get()->info('参数：' . json_encode($params));
        if (!isset($params['code'])) {
            Log::get()->info('error: no code');
            return false;
        }

        $this->client->setClientId(config('google.client_id'));
        $this->client->setScopes('email profile');
        $this->client->setClientSecret(config('google.client_secret'));
        $this->client->setRedirectUri(config('google.grant_domain') . '/api/v1/avenue/google/auth/notify');
        $res = $this->client->fetchAccessTokenWithAuthCode($params['code']);

        Log::get()->info('auth code:' . json_encode($res));

        //获取用户信息
        $infoJson = $this->tokenInfo($res['id_token'] ?? '');
        $info = json_decode($infoJson, true);

        //保存用户信息
        $userInfo = make(AvenueUser::class)->where('email', $info['email'])->first();
        $saveData = Utils::setSaveData([
            'nick_name' => $info['name'],
            'email' => $info['email'],
            'avatar' => $info['picture'],
        ]);
        if ($userInfo) {
            $userInfo->save($saveData);
        } else {
            $userInfo = make(AvenueUser::class)->save($saveData);
        }

        //生成并保存token
        $token = $this->jwt->getToken([
            'user_id' => $userInfo->id,
            'email' => $userInfo->email,
            'nick_name' => $userInfo->nick_name,
        ]);
        $userTokenSaveData = Utils::setSaveData([
            'user_id' => $userInfo->id,
            'token' => $token,
        ]);
        $userTokenInfo = make(AvenueUserToken::class)->where('user_id', $userInfo->id)->first();
        if ($userTokenInfo) {
            $userTokenInfo->save($userTokenSaveData);
        } else {
            make(AvenueUserToken::class)->save($userTokenSaveData);
        }

        Log::get()->info('======== google notify end ======');
        header('Location: ' . config('google.grant_domain'));
    }

    public function tokenInfo($idToken)
    {
        $url = self::TOKENINFO_URL . '?id_token=' . $idToken;
        $data = Utils::httpRequest($url);
        return $data;
    }
}