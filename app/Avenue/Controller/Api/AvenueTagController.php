<?php


namespace App\Avenue\Controller\Api;

use App\Package\Verify;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Mine\MineController;

#[Controller(prefix: 'api/avenue/tag')]
class AvenueTagController extends MineController
{
    #[Inject]
    public \App\Avenue\Service\AvenueTagService $service;

    #[Inject]
    public Verify $verify;

    #[RequestMapping(path: "index", methods: "get")]
    public function index()
    {
        try {
            $params = $this->verify->requestParams([
                ['onlyMenu',  ''],
                ['type',  ''],
            ], $this->request);
            $list = $this->service->getPageList($params, false);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}