<?php


namespace App\Avenue\Controller\Admin;



use Mine\MineController;
use App\Avenue\Service\AvenueSearchCategoryService;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Mine\Middlewares\CheckModuleMiddleware;
use Mine\Annotation\Auth;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Di\Annotation\Inject;
use Mine\Annotation\Permission;
use App\Package\Verify;

#[Controller(prefix: 'avenue/searchCategory'), Auth]
#[Middleware(middleware: CheckModuleMiddleware::class)]
class AvenueSearchCategoryController extends MineController
{
    #[Inject]
    public AvenueSearchCategoryService $service;

    #[Inject]
    public Verify $verify;

    #[RequestMapping(path: "index", methods: "get"),  Permission('avenue:searchCategory:index, avenue:searchCategory:index')]
    public function index()
    {
        try {
            $params = $this->verify->requestParams([
                ['title', ''],
                ['is_all', ''],
            ], $this->request);
            $list = $this->service->getList($params);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    #[RequestMapping(path: "read/{id}", methods: "get"),  Permission('avenue:searchCategory:read')]
    public function read($id)
    {
        try {
            $list = $this->service->getOne($id);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    #[RequestMapping(path: "save", methods: "post"),  Permission('avenue:searchCategory:save')]
    public function save()
    {
        try {
            $params = $this->verify->requestParams([
                ['title', ''],
                ['sort', 0],
            ], $this->request);
            $this->verify->check($params, [
                'title' => 'required|between:1,8',
                'sort' => 'integer',
            ], []);
            $this->service->add($params);
            return $this->success([]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    #[RequestMapping(path: "update/{id}", methods: "put"),  Permission('avenue:searchCategory:update')]
    public function update(int $id)
    {
        try {
            $params = $this->verify->requestParams([
                ['title', ''],
                ['sort', 0],
            ], $this->request);
            $this->verify->check($params, [
                'title' => 'required|between:1,3',
                'sort' => 'integer',
            ], []);
            $this->service->edit($id, $params);
            return $this->success([]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    #[RequestMapping(path: "delete", methods: "delete"),  Permission('search:engine:category:delete')]
    public function delete()
    {
        try {
            $params = $this->verify->requestParams([
                ['ids', ''],
            ], $this->request);
            $this->verify->check($params, [
                'ids' => 'required',
            ], []);
            $this->service->delete($params);
            return $this->success([]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}