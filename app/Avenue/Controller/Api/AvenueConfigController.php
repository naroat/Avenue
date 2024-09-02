<?php


namespace App\Avenue\Controller\Api;

use App\Package\Verify;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Mine\MineController;

#[Controller(prefix: 'api/avenue/config')]
class AvenueConfigController extends MineController
{
    #[Inject]
    public \App\Avenue\Service\AvenueConfigService $service;

    #[Inject]
    public Verify $verify;

    #[RequestMapping(path: "getConfigByCode", methods: "get")]
    public function getConfigByCode()
    {
        try {
            $params = $this->verify->requestParams([
                ['code',  ''],
            ], $this->request);
            $this->verify->check($params, [
                'code' => 'required'
            ], []);
            $list = $this->service->getConfigByCode($params);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}