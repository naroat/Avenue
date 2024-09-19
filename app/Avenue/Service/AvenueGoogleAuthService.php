<?php
declare(strict_types=1);

namespace App\Avenue\Service;

use App\Avenue\Mapper\AvenueUserMapper;
use App\Avenue\Mapper\AvenueUserTokenMapper;
use App\Avenue\Model\AvenueUser;
use App\Avenue\Model\AvenueUserToken;
use App\Package\Log;
use App\Package\Utils;
use App\Package\Verify;
use Hyperf\DbConnection\Annotation\Transactional;
use Hyperf\Di\Annotation\Inject;
use Mine\Abstracts\AbstractService;
use Google;
use Xmo\JWTAuth\JWT;
use function Hyperf\Support\make;

class AvenueGoogleAuthService extends AbstractService
{
    #[Inject]
    public Google\Client $client;

    protected \Xmo\JWTAuth\JWT $jwt;

    #[Inject]
    protected AvenueUserMapper $userMapper;

    #[Inject]
    protected AvenueUserTokenMapper $userTokenMapper;

    const TOKENINFO_URL = 'https://www.googleapis.com/oauth2/v3/tokeninfo';

    public function __construct(string $scene = 'default')
    {
        /* @var JWT $this ->jwt */
        $this->jwt = make(JWT::class)->setScene($scene);
    }
    public function authUrl()
    {
        $state = bin2hex(random_bytes(16));
        $this->client->setState($state);
        $this->client->setClientId(config('google.client_id'));
        $this->client->setScopes('email profile');
        $this->client->setRedirectUri(config('google.grant_domain') . '/v1/avenue/google/auth/notify');
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
            Log::get()->error('no code');
            return false;
        }

        /*$this->client->setClientId(config('google.client_id'));
        $this->client->setScopes('email profile');
        $this->client->setClientSecret(config('google.client_secret'));
        $this->client->setRedirectUri(config('google.grant_domain') . '/v1/avenue/google/auth/notify');
        $res = $this->client->fetchAccessTokenWithAuthCode($params['code']);*/

        $res = [
            'id_token' => 'eyJhbGciOiJSUzI1NiIsImtpZCI6ImQ3YjkzOTc3MWE3ODAwYzQxM2Y5MDA1MTAxMmQ5NzU5ODE5MTZkNzEiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIxMjU0MDEyMjMzMy1uOXY0OWl0M2ZocHVldHY5dXNqMDA5YTRhbDNyMWM2Ni5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsImF1ZCI6IjEyNTQwMTIyMzMzLW45djQ5aXQzZmhwdWV0djl1c2owMDlhNGFsM3IxYzY2LmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwic3ViIjoiMTExNjA1NzM3NzM5NTg4NDQ2NTEyIiwiZW1haWwiOiJ0YW9yYW4xNDAxQGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJhdF9oYXNoIjoidEs0VHBGX2kzUFowLTJDUm00Nm1jZyIsIm5hbWUiOiJ0YW8gdGFvIiwicGljdHVyZSI6Imh0dHBzOi8vbGgzLmdvb2dsZXVzZXJjb250ZW50LmNvbS9hL0FDZzhvY0k2dmp2WFNwRW9MVHYxMy1Sd0RxOVIxOFR5ZTVuSF9kWE9CeGx5djZwSmdFYjUydz1zOTYtYyIsImdpdmVuX25hbWUiOiJ0YW8iLCJmYW1pbHlfbmFtZSI6InRhbyIsImlhdCI6MTcyNjIxMDQ5MiwiZXhwIjoxNzI2MjE0MDkyfQ.pnJQmVAXcDgFem2iPv9vBwpTqH_rlLS8522rZspa4JtXvBbZ7zBFOSRfwA1-IDozRyHTJytiLcKXUYwjKO13TudKJsTvV7IyPxVB1vXzEWbpnWnpraf-gH7aXTCqyHY7Um0DJAzdnxkIHfRegARd51ycl5eS3lpJx66msIWxTXV9YAkMqy_wWhj2iS2RaMmCsgSSJxGTnMkt_V4e20Bk3wUFloy0PHCLTUjst9DPJoDyb6DZ1ajJrmTEiK_e_1QfBks5YqVfSqMGMo9Yh2OEJCdMTzdLD-CiRmiY6La7h8fPq2k5I8XkjEsMS74ty31K-XF966au2dpe2kaOlvTUcg'
        ];

        Log::get()->info('auth code:' . json_encode($res));

        //获取用户信息
        $tokeninfo = $this->tokenInfo($res['id_token'] ?? '');
        Log::get()->info($tokeninfo);
        if ($tokeninfo === false) {
            Log::get()->error('get tokeninfo fail');
            return false;
        }
        $info = json_decode($tokeninfo, true);
        if (isset($info['error_description'])) {
            Log::get()->error($info['error_description']);
            return false;
        }

        //保存用户信息
        $userInfo = $this->userMapper->getModel()->where('email', $info['email'])->first();
        $saveData = Utils::setSaveData([
            'nick_name' => $info['name'],
            'email' => $info['email'],
            'avatar' => $info['picture'],
        ]);

        if ($userInfo) {
            $this->userMapper->update($userInfo->id, $saveData);
            $userId = $userInfo->id;
        } else {
            $userId = $this->userMapper->save($saveData);
        }

        //生成并保存token
        $token = $this->jwt->getToken([
            'id' => $userId,
        ]);
        $userTokenSaveData = Utils::setSaveData([
            'user_id' => $userId,
            'token' => $token,
        ]);
        $userTokenInfo = $this->userTokenMapper->getModel()->where('user_id', $userId)->first();
        if ($userTokenInfo) {
            $this->userTokenMapper->update($userTokenInfo->id, $userTokenSaveData);
        } else {
            $this->userTokenMapper->save($userTokenSaveData);
        }

        Log::get()->info('======== google notify end ======');
        return true;
    }

    public function tokenInfo($idToken)
    {
        $url = self::TOKENINFO_URL . '?id_token=' . $idToken;
        $option = [];
        /*if (config('curl_proxy.enable') == true) {
            $option['proxy'] = [
                'ip' => config('curl_proxy.host'),
                'port' => config('curl_proxy.port'),
                'type' => config('curl_proxy.type'),
            ];
        }*/
        $data = Utils::httpRequest($url, 'get', false, $option);
        return $data;
    }
}