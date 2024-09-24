<?php


namespace App\Avenue\Controller\Api;

use App\Package\Verify;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Mine\MineController;

#[Controller(prefix: 'v1/avenue/product')]
class AvenueProductController extends MineController
{
    #[Inject]
    public \App\Avenue\Service\AvenueProductService $service;

    #[Inject]
    public Verify $verify;

    #[RequestMapping(path: "index", methods: "get")]
    public function index()
    {
        try {
            $params = $this->verify->requestParams([
                ['title',  ''],
                ['cate_id', ''],
                ['tag_id', ''],
                ['sort', 1],    //1最新，2最热
                ['status', '1'],
                ['limit', 36],
            ], $this->request);
            $list = $this->service->getPageList($params, false);
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

    #[RequestMapping(path: "submit", methods: "post")]
    public function submit()
    {
        try {
            $params = $this->verify->requestParams([
                ['title', ''],
                ['link', ''],
                ['desc', ''],
                ['tags', ''],
                ['status', 2],
            ], $this->request);
            $list = $this->service->save($params);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    #[RequestMapping(path: "fetchProduct", methods: "post")]
    public function fetchProduct()
    {
        try {
            $params = $this->verify->requestParams([
                ['url',  ''],
            ], $this->request);
            $this->verify->check($params, [
                'url' => 'required|url'
            ], []);
            $list = $this->service->fetchProduct($params);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}