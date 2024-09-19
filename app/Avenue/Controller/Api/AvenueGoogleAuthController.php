<?php


namespace App\Avenue\Controller\Api;

use App\Package\Verify;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Mine\MineController;

#[Controller(prefix: 'v1/avenue/google/auth')]
class AvenueGoogleAuthController extends MineController
{
    #[Inject]
    public \App\Avenue\Service\AvenueGoogleAuthService $service;

    #[Inject]
    public Verify $verify;

    /**
     * 创建状态令牌
     */
    public function createStateToken()
    {
        $state = bin2hex(random_bytes(128/8));
        $app = [];
        $app['session']->set('state', $state);
        return $app['twig']->render('index.html', array(
            'CLIENT_ID' => config('google.client_id'),
            'STATE' => $state,
            'APPLICATION_NAME' => config('google.application_name'),
        ));
    }

    #[RequestMapping(path: "notify", methods: "get")]
    public function notify(ResponseInterface $response)
    {
/*        array(4) {
                ["code"]=>
          string(73) "4/0AQlEd8ySff4c3HRu1jIHnErWO9qkk4IWdNnomVy9SUOIZtCWhGWtH5XjhYx6znTkcEoSsw"
                ["scope"]=>
          string(116) "email profile openid https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email"
                ["authuser"]=>
          string(1) "0"
                ["prompt"]=>
          string(7) "consent"
        }*/
//        header('Location: ' . config('google.grant_domain'));


        try {
            $list = $this->service->notify($this->request->all());
            return $response->redirect('http://www.baidu.com');
//            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function getUserInfo()
    {

    }
}