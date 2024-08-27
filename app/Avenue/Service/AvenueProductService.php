<?php


namespace App\Avenue\Service;

use App\Avenue\Model\AvenueProduct;
use App\Avenue\Model\AvenueProductCategory;
use App\Avenue\Model\AvenueProductTagRealtion;
use App\Package\Utils;
use Hyperf\DbConnection\Db;

class AvenueProductService
{
    private function getModel()
    {
        return make(AvenueProduct::class);
    }

    public function getList($params)
    {
        $list = $this->getModel()->with('productCate');
        if (!empty($params['title']) && $params['title'] != '') {
            $list = $list->where('title', 'like', "%{$params['title']}%");
        }
        if (!empty($params['cate_id']) && $params['cate_id'] != '') {
            $list = $list->where('cate_id', $params['cate_id']);
        }
        if (!empty($params['sort']) && $params['sort'] != '') {
            if ($params['sort'] == 1) {
                $list = $list->orderBy('created_at', 'desc');
            } elseif ($params['sort'] == 2) {
                $list = $list->orderBy('click', 'desc');
            }
        }
        $list = $list->paginate(36);
        $list->each(function ($item) {
            $item->new = 0;
            if ($item->created_at >= date('Y-m-d 00:00:00', time())) {
                $item->new = 1;
            }
            $item->cate_text = $item->productCate->title ?? '';
            $item->tags = array_map(function ($item) {
                return (int)$item;
            }, array_values(array_filter(explode(',', $item->tags))));
        });
        return CommonService::buildPaginate($list);
    }

    public function getOne($id)
    {
        return $this->getModel()->find($id);
    }

    public function add($params)
    {
        $tags = ',' . implode(',', $params['tags']) . ',';

        Db::transaction(function () use ($params, $tags) {
            $model = $this->getModel();
            Utils::setSaveData($model, [
                'title' => $params['title'],
                'desc' => $params['desc'],
                'logo' => $params['logo'],
                'link' => $params['link'],
                'cate_id' => $params['cate_id'],
                'sort' => $params['sort'],
                'tags' => $tags,
            ]);
            $model->save();

            //insert标签
            $this->insertTags($model->id, $params['tags']);
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
            $tags = ',' . implode(',', $params['tags']) . ',';
            Utils::setSaveData($info, [
                'id' => $params['id'],
                'title' => $params['title'],
                'desc' => $params['desc'],
                'logo' => $params['logo'],
                'link' => $params['link'],
                'sort' => $params['sort'],
                'cate_id' => $params['cate_id'],
                'tags' => $tags,
            ]);
            $info->save();

            //删除旧标签
            $this->deleteTags($info->id);
            //insert标签
            $this->insertTags($info->id, $params['tags']);
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
            //删除标签
            $this->deleteTags($id);
        }
        return true;
    }

    /**
     * 删除对应产品标签
     *
     * @param $productId
     */
    public function deleteTags($productId)
    {
        make(AvenueProductTagRealtion::class)->where('product_id', $productId)->delete();
    }

    /**
     * 插入标签
     *
     * @param $productId
     * @param $tags
     */
    public function insertTags($productId, $tags)
    {
        $saveTags = [];
        foreach ($tags as $tagId) {
            $saveTags[] = [
                'product_id' => $productId,
                'tag_id' => $tagId
            ];
        }
        make(AvenueProductTagRealtion::class)->insert($saveTags);
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