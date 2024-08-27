<?php


namespace App\Avenue\Service;

use App\Avenue\Model\AvenueConfig;
use App\Package\Utils;
use Hyperf\DbConnection\Db;

class AvenueConfigService
{
    private function getModel()
    {
        return make(AvenueConfig::class);
    }

    public function getList($params)
    {
        $list = $this->getModel()->where('is_show', 1);
        if (!empty($params['code']) && $params['code'] != '') {
            $list = $list->where('code', 'like', "%{$params['code']}%");
        }
        if (!empty($params['desc']) && $params['desc'] != '') {
            $list = $list->where('desc', 'like', "%{$params['desc']}%");
        }
        $list = $list->paginate(10);
        $list->each(function ($item) {
            if (in_array($item->code, AvenueConfig::NEED_HTML_DECODE) && !empty($item->value)) {
                $item->value = htmlspecialchars_decode($item->value);
            }
        });
        return CommonService::buildPaginate($list);
    }

    public function getOne($id)
    {
        $data = $this->getModel()->find($id);
        if (in_array($data->code, AvenueConfig::NEED_HTML_DECODE)) {
            $data->value = htmlspecialchars_decode($data->value);
        }
        return $data;
    }

    public function edit($id, $params)
    {
        $model = $this->getModel();
        $info = $model->find($id);
        if (!$info) {
            throw new \Exception("信息不存在");
        }
        //save
        Utils::setSaveData($info, [
            'value' => $params['value'],
        ]);
        $info->save();
        return true;
    }

    public function getConfigByCode($code)
    {
        $data = $this->getModel()->where('code', $code)->find();
        if (!$data) {
            throw new \Exception('数据不存在');
        }
        return $data;
    }

    public function setConfigByCode($code, $param)
    {
        $data = $this->getConfigByCode($code);
        Utils::setSaveData($data, [
            'value' => $param['value'],
        ]);
        $data->save();
    }
}