<?php


namespace App\Avenue\Service;

use App\Avenue\Model\AvenueProduct;
use App\Avenue\Model\AvenueProductCategory;
use App\Avenue\Model\AvenueProductTagRealtion;
use App\Package\Utils;
use Hyperf\DbConnection\Db;

class AvenueProductCategoryService
{
    private function getModel()
    {
        return make(AvenueProductCategory::class);
    }

    public function getList($params)
    {
        $list = $this->getModel();
        if (!empty($params['title']) && $params['title'] != '') {
            $list = $list->where('title', 'like', "%{$params['title']}%");
        }
        if ($params['p_id'] != '') {
            $list = $list->where('p_id', $params['p_id']);
        }

        if (!empty($params['is_all']) && $params['is_all'] != '') {
            $list = $list->get();
        } else {
            $list = $list->with('children')->where('p_id', 0)->get();
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
            throw new \Exception('该分类已存在');
        }
        Utils::setSaveData($model, [
            'title' => $params['title'],
            'p_id' => $params['p_id'],
            'sort' => $params['sort'],
        ]);
        $res = $model->save();
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
            'p_id' => $params['p_id'],
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
            $childExists = make(AvenueArticleCategory::class)->where('p_id', $id)->exists();
            if ($childExists) {
                throw new \Exception("[{$info->title}]存在下级分类，请删除后再进行该操作！");
            }

            $articleExists = make(AvenueArticle::class)->where('cate_id', $id)->exists();
            if ($articleExists) {
                throw new \Exception("[{$info->title}]存在文章，请删除后再进行该操作！");
            }
            $info->delete();
        }
        return true;
    }
}