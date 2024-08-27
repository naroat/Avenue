<?php


namespace App\Avenue\Service;

use App\Avenue\Model\AvenueSearch;
use App\Avenue\Model\AvenueSearchCategory;
use App\Package\Utils;

class AvenueSearchService
{
    private function getModel()
    {
        return make(AvenueSearch::class);
    }

    public function getList($params)
    {
        $list = $this->getModel();
        if (!empty($params['title']) && $params['title'] != '') {
            $list = $list->where('title', 'like', "%{$params['title']}%");
        }
        $list = $list->paginate(10);
        return CommonService::buildPaginate($list);
    }

    public function getOne($id)
    {
        return $this->getModel()->find($id);
    }

    public function add($params)
    {
        $model = $this->getModel();
        Utils::setSaveData($model, [
            'title' => $params['title'],
            'desc' => $params['desc'],
            'logo' => $params['logo'],
            'link' => $params['link'],
            'cate_id' => $params['cate_id'],
            'sort' => $params['sort'],
        ]);
        $model->save();
        return true;
    }

    public function edit($params)
    {
        //exists
        $model = $this->getModel();
        $info = $model->find($params['id']);
        if (!$info) {
            throw new \Exception("信息不存在");
        }

        //save
        Utils::setSaveData($info, [
            'id' => $params['id'],
            'title' => $params['title'],
            'desc' => $params['desc'],
            'logo' => $params['logo'],
            'link' => $params['link'],
            'sort' => $params['sort'],
            'cate_id' => $params['cate_id'],
        ]);
        $info->save();

        return true;
    }

    public function delete($params)
    {
        $info = $this->getModel()->find($params['id']);
        if (!$info) {
            throw new \Exception('信息不存在');
        }
        $info->delete();
        return true;
    }

    public function treeList($params)
    {
        $list = make(AvenueSearchCategory::class)->with(['engine' => function ($query) {
            $query->orderBy('sort', 'desc');
        }]);
        $list = $list->orderBy('sort', 'desc')->get();
        return $list;
    }
}