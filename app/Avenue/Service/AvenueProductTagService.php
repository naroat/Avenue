<?php


namespace App\Avenue\Service;


use App\Avenue\Model\AvenueProduct;
use App\Package\Utils;
use App\Avenue\Model\AvenueProductTag;

class AvenueProductTagService
{
    private function getModel()
    {
        return make(AvenueProductTag::class);
    }

    public function getList($params)
    {
        $list = $this->getModel();
        if (!empty($params['title']) && $params['title'] != '') {
            $list = $list->where('title', 'like', "%{$params['title']}%");
        }
        if (!empty($params['is_all']) && $params['is_all'] == 1) {
            $list = $list->get();
        } else {
            $list = $list->paginate(10);
            $list = CommonService::buildPaginate($list);
        }
        return $list;
    }

    public function getOne($id)
    {
        return $this->getModel()->find($id);
    }

    public function add($params)
    {
        $model = $this->getModel();
        if ($model->where('title', $params['title'])->exists()) {
            throw new \Exception('该标签已存在');
        }
        Utils::setSaveData($model, [
            'title' => $params['title'],
            'textcolor' => $params['textcolor'],
            'color' => $params['color'],
            'sort' => $params['sort'],
        ]);
        $model->save();
        return true;
    }

    public function edit($id, $params)
    {
        $model = $this->getModel();
        //exists
        $info = $model->find($id);
        if (!$info) {
            throw new \Exception("数据不存在");
        }

        if ($model->where('title', $params['title'])->where('id', '<>', $id)->exists()) {
            throw new \Exception('该名称已存在');
        }

        //save
        Utils::setSaveData($info, [
            'id' => $id,
            'title' => $params['title'],
            'textcolor' => $params['textcolor'],
            'color' => $params['color'],
            'sort' => $params['sort'],
        ]);
        $info->save();
        return true;
    }

    public function delete($params)
    {
        foreach ($params['ids'] as $id) {
            $info = $this->getModel()->find($id);
            if (!$info) {
                throw new \Exception('数据不存在');
            }
            $engineExists = make(AvenueProduct::class)->where('cate_id', $id)->exists();
            if ($engineExists) {
                throw new \Exception("[{$info->title}]下存在数据，请删除分类下数据后再删除！");
            }
            $info->delete();
        }
        return true;
    }
}