<?php


namespace App\Avenue\Controller\Api;

use App\Avenue\Service\AvenueSearchService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Di\Annotation\Inject;
use Mine\MineController;

#[Controller(prefix: 'api/avenue/search')]
class AvenueSearchController extends MineController
{
    #[Inject]
    public AvenueSearchService $service;

    #[RequestMapping(path: "index", methods: "get")]
    public function index()
    {
        try {
            $list = $this->service->treeList([]);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}