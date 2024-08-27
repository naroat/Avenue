<?php


namespace App\Avenue\Service;

use App\Avenue\Model\AvenueArticle;
use App\Package\Utils;
use Hyperf\DbConnection\Db;

class AvenueArticleService
{
    private function getModel()
    {
        return make(AvenueArticle::class);
    }

    public function getList($params)
    {
        $list = $this->getModel()->with('avenueArticleCategory');
        if (!empty($params['title']) && $params['title'] != '') {
            $list = $list->where('title', 'like', "%{$params['title']}%");
        }
        if (!empty($params['cate_id']) && $params['cate_id'] != '') {
            $list = $list->where('cate_id', $params['cate_id']);
        }
        $list = $list->paginate(36);
        return CommonService::buildPaginate($list);
    }

    public function getOne($id)
    {
        return $this->getModel()->find($id);
    }

    public function add($params)
    {
        Db::transaction(function () use ($params) {
            $model = $this->getModel();
            Utils::setSaveData($model, [
                'title' => $params['title'],
                'desc' => $params['desc'],
                'content' => $params['content'],
                'cate_id' => $params['cate_id'],
            ]);
            $model->save();
        });


        return true;
    }

    public function edit($params)
    {
        $model = $this->getModel();
        $info = $model->find($params['id']);
        if (!$info) {
            throw new \Exception("信息不存在");
        }
        Db::transaction(function () use ($info, $params) {
            //save
            Utils::setSaveData($info, [
                'id' => $params['id'],
                'title' => $params['title'],
                'content' => $params['desc'],
                'content' => $params['content'],
                'cate_id' => $params['cate_id'],
            ]);
            $info->save();
        });
        return true;
    }

    public function delete($params)
    {
        foreach ($params['ids'] as $id) {
            $info = $this->getModel()->find($id);
            if (!$info) {
                throw new \Exception('数据不存在');
            }
            $info->delete();
        }
        return true;
    }

    public function addClick($id)
    {
        $model = $this->getModel();
        $info = $model->find($id);
        if (!$info) {
            throw new \Exception("信息不存在");
        }
        Utils::setSaveData($info, [
            'click' => $info->click + 1,
        ]);
        $info->save();
        return true;
    }
}