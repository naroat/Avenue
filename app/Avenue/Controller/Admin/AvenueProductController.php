<?php


namespace App\Avenue\Controller\Admin;

use Mine\Middlewares\CheckModuleMiddleware;
use Hyperf\Di\Annotation\Inject;
use App\Package\Verify;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Avenue\Service\AvenueProductService;
use Mine\MineController;
use Mine\Annotation\Auth;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Mine\Annotation\Permission;

#[Controller(prefix: 'avenue/product'), Auth]
#[Middleware(middleware: CheckModuleMiddleware::class)]
class AvenueProductController extends MineController
{
    #[Inject]
    public AvenueProductService $service;

    #[Inject]
    public Verify $verify;

    #[RequestMapping(path: "index", methods: "get"),  Permission('avenue:product, avenue:product:index')]
    public function index()
    {
        $params = $this->verify->requestParams([
            ['title', ''],
        ], $this->request);
        try {
            $list = $this->service->getList($params);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    #[RequestMapping(path: "read/{id}", methods: "get"),  Permission('avenue:product:read')]
    public function read($id)
    {
        try {
            $list = $this->service->getOne($id);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    #[RequestMapping(path: "save", methods: "post"),  Permission('avenue:product:store')]
    public function save()
    {
        try {
            $params = $this->verify->requestParams([
                ['title', ''],
                ['desc', ''],
                ['link', ''],
                ['logo', ''],
                ['cate_id', 0],
                ['tags', ''],
                ['sort', 0],
            ], $this->request);
            $this->verify->check($params, [
                'title' => 'required|between:1,8',
                'link' => 'required|url',
                'sort' => 'integer',
                'cate_id' => 'required|integer',
                'tags' => 'required|array',
            ], [
                'link.url' => '不是有效的链接格式',
                'cate_id.required' => '请选择分类',
            ]);
            $this->service->add($params);
            return $this->success([]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    #[RequestMapping(path: "update/{id}", methods: "put"),  Permission('avenue:product:update')]
    public function update()
    {
        try {
            $params = $this->verify->requestParams([
                ['id', ''],
                ['title', ''],
                ['desc', ''],
                ['logo', ''],
                ['link', ''],
                ['cate_id', 0],
                ['tags', ''],
                ['sort', 0],
            ], $this->request);
            $this->verify->check($params, [
                'id' => 'required',
                'title' => 'required|between:1,8',
                'link' => 'required|url',
                'sort' => 'integer',
                'cate_id' => 'required|integer',
                'tags' => 'required|array',
            ], [
                'link.url' => '不是有效的链接格式',
                'cate_id.required' => '请选择分类',
            ]);
            $this->service->edit($params);
            return $this->success([]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * @RequestMapping(path="destroy", methods="post")
     */

    #[RequestMapping(path: "delete", methods: "delete"),  Permission('avenue:product:delete')]
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

    #[RequestMapping(path: "treelist", methods: "get"),  Permission('avenue:product:treelist')]
    public function treeList()
    {
        try {
            $list = $this->service->treeList([]);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}