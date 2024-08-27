<?php


namespace App\Avenue\Controller\Admin;

use App\Avenue\Service\AvenueConfigService;
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

#[Controller(prefix: 'avenue/config'), Auth]
#[Middleware(middleware: CheckModuleMiddleware::class)]
class AvenueConfigController extends MineController
{
    #[Inject]
    public AvenueConfigService $service;

    #[Inject]
    public Verify $verify;

    #[RequestMapping(path: "index", methods: "get"),  Permission('avenue:config, avenue:config:index')]
    public function index()
    {
        $params = $this->verify->requestParams([
            ['code', ''],
            ['desc', ''],
        ], $this->request);
        try {
            $list = $this->service->getList($params);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    #[RequestMapping(path: "read/{id}", methods: "get"),  Permission('avenue:config:read')]
    public function read($id)
    {
        try {
            $list = $this->service->getOne($id);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    #[RequestMapping(path: "update/{id}", methods: "put"),  Permission('avenue:config:update')]
    public function update($id)
    {
        try {
            $params = $this->verify->requestParams([
                ['value', ''],
            ], $this->request);
            $this->verify->check($params, [
                'value' => 'required',
            ], []);
            $this->service->edit($id, $params);
            return $this->success([]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    #[RequestMapping(path: "getConfigByCode", methods: "get"),  Permission('avenue:config:getConfigByCode')]
    public function getConfigByCode()
    {
        try {
            $params = $this->verify->requestParams([
                ['code', ''],
            ], $this->request);
            $this->verify->check($params, [
                'code' => 'required',
            ]);
            $list = $this->service->getConfigByCode($params['code']);
            return $this->success($list);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    #[RequestMapping(path: "setConfigByCode", methods: "post"),  Permission('avenue:config:setConfigByCode')]
    public function setConfigByCode()
    {
        try {
            $params = $this->verify->requestParams([
                ['code', ''],
                ['value', ''],
            ], $this->request);
            $this->verify->check($params, [
                'code' => 'required',
                'value' => 'required',
            ]);
            $this->service->setConfigByCode($params['code'], $params);
            return $this->success([]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}