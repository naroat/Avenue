<?php


namespace App\Avenue\Controller\Api;

use App\Avenue\Service\AvenueProductService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Di\Annotation\Inject;
use Mine\MineController;
use App\Package\Verify;
use App\Avenue\Service\AvenueProductCategoryService;

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
            $list = $this->service->getList($params);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}