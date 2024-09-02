<?php


namespace App\Avenue\Controller\Api;

use App\Avenue\Service\AvenueProductCategoryService;
use App\Package\Verify;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Mine\MineController;

#[Controller(prefix: 'api/avenue/productCategory')]
class AvenueProductCategoryController extends MineController
{
    #[Inject]
    public AvenueProductCategoryService $service;

    #[Inject]
    public Verify $verify;

    #[RequestMapping(path: "index", methods: "get")]
    public function index()
    {
        try {
            $params = $this->verify->requestParams([
                ['is_all', ''],
            ], $this->request);
            $list = $this->service->getList($params, false);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}