<?php
declare(strict_types=1);

namespace App\Avenue\Service;

use App\Package\Log;
use App\Package\Utils;
use Firebase\JWT\JWT;
use Hyperf\Di\Annotation\Inject;
use Mine\Abstracts\AbstractService;
use Google;

class AvenueGoogleAuthService extends AbstractService
{
    #[Inject]
    public Google\Client $client;

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

    public function notify($params)
    {
        Log::get()->info('========google notiify start ======');
        Log::get()->info('参数：' . json_encode($params));
        if (!isset($params['code'])) {
            Log::get()->info('error: no code');
            return false;
        }
//        $this->client->setAuthConfigFile();
//        $this->client->setAuthConfig();
        $this->client->setClientId(config('google.client_id'));
        $this->client->setScopes('email profile');
        $this->client->setClientSecret(config('google.client_secret'));
        $this->client->setRedirectUri(config('google.grant_domain') . '/api/v1/avenue/google/auth/notify');
        $res = $this->client->fetchAccessTokenWithAuthCode($params['code']);
        Log::get()->info('auth code:' . json_encode($res));

//        $accessToken = $this->client->getAccessToken();
//
//        Log::get()->info('access_token:' . json_encode($accessToken));

        //获取用户信息
//        $accessToken = $res['access_token'] ?? '';
        $oauthService = $this->client->getOAuth2Service();
        $oauthService->setIdToken($res['id_token']);
        $info = $oauthService->verifyIdToken();
        Log::get()->info("id token解析: " . json_encode($info));
        //保存数据库

        Log::get()->info('======== google notiify end ======');
    }

    public function tokenInfo()
    {
        $idToken = 'eyJhbGciOiJSUzI1NiIsImtpZCI6ImQ3YjkzOTc3MWE3ODAwYzQxM2Y5MDA1MTAxMmQ5NzU5ODE5MTZkNzEiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIxMjU0MDEyMjMzMy1uOXY0OWl0M2ZocHVldHY5dXNqMDA5YTRhbDNyMWM2Ni5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsImF1ZCI6IjEyNTQwMTIyMzMzLW45djQ5aXQzZmhwdWV0djl1c2owMDlhNGFsM3IxYzY2LmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwic3ViIjoiMTExNjA1NzM3NzM5NTg4NDQ2NTEyIiwiZW1haWwiOiJ0YW9yYW4xNDAxQGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJhdF9oYXNoIjoib1Q0a292ZDZCVHBrdmpwRkxoektGdyIsIm5hbWUiOiJ0YW8gdGFvIiwicGljdHVyZSI6Imh0dHBzOi8vbGgzLmdvb2dsZXVzZXJjb250ZW50LmNvbS9hL0FDZzhvY0k2dmp2WFNwRW9MVHYxMy1Sd0RxOVIxOFR5ZTVuSF9kWE9CeGx5djZwSmdFYjUydz1zOTYtYyIsImdpdmVuX25hbWUiOiJ0YW8iLCJmYW1pbHlfbmFtZSI6InRhbyIsImlhdCI6MTcyNjEzMDcwMywiZXhwIjoxNzI2MTM0MzAzfQ.GnesEION30aBWavp2M5e3LOAkX-kPUMFv3IlXWbUEAfhZ_cnG3noCTv_MbRRRtJ3aBgATqdPPAEzGsrsq4wCtFJnTc2aeYmLen5EB55kf1h9IEbqQYHK5KdsJ-Ft4h9B2Aw5lI542BBu2B9oUOMBRM4NXaCDdDwvV5ReojI5vjE96SmfDLvJzValB-Fo77OlGfhRkFR1Px4hTqMKVYH41Rfshv6oxkf0Th60mA2rXcqtQuuV_VqoryRWIP5e30LopL_eaGfWB8kbMYxbgnDXt-HQPNuN0-6COezm4LbJ1uZoRMtH2LIVSuocVHC2SE5BVgK3qa3ygWcVPh1QZJ4w8g';
        $url = self::TOKENINFO_URL . '?id_token=' . $idToken;
        $data = Utils::httpRequest($url);

        Log::get()->info("id token解析: " . json_encode($data));
    }
}