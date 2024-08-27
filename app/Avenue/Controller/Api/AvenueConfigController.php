<?php


namespace App\Avenue\Controller\Api;

use App\Avenue\Service\AvenueProductService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Di\Annotation\Inject;
use Mine\MineController;
use App\Package\Verify;

#[Controller(prefix: 'api/avenue/config')]
class AvenueConfigController extends MineController
{
    #[Inject]
    public AvenueProductService $service;

    #[Inject]
    public Verify $verify;

    #[RequestMapping(path: "index", methods: "get")]
    public function index()
    {
        try {
            $params = $this->verify->requestParams([
                ['title',  ''],
                ['cate_id', 0],
                ['sort', 0],    //0默认，1最新，2最热
            ], $this->request);
            $list = $this->service->getList($params);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    #[RequestMapping(path: "addClick/{id}", methods: "put")]
    public function addClick($id)
    {
        try {
            $list = $this->service->addClick($id);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}