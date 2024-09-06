<?php
declare(strict_types=1);
/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo<root@imoi.cn>
 * @Link   https://gitee.com/xmo/MineAdmin
 */

namespace App\Avenue\Mapper;

use App\Avenue\Model\AvenueProduct;
use Hyperf\Database\Model\Builder;
use Mine\Abstracts\AbstractMapper;

/**
 * 产品管理Mapper类
 */
class AvenueProductMapper extends AbstractMapper
{
    /**
     * @var AvenueProduct
     */
    public $model;

    public function assignModel()
    {
        $this->model = AvenueProduct::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        
        // ID
        if (isset($params['id']) && filled($params['id'])) {
            $query->where('id', '=', $params['id']);
        }

        // 名称
        if (isset($params['title']) && filled($params['title'])) {
            $query->where('title', 'like', '%'.$params['title'].'%');
        }

        // 描述
        if (isset($params['desc']) && filled($params['desc'])) {
            $query->where('desc', 'like', '%'.$params['desc'].'%');
        }

        // logo
        if (isset($params['logo']) && filled($params['logo'])) {
            $query->where('logo', 'like', '%'.$params['logo'].'%');
        }

        // 链接
        if (isset($params['link']) && filled($params['link'])) {
            $query->where('link', '=', $params['link']);
        }

        // status
        if (isset($params['status']) && filled($params['status'])) {
            $query->where('status', '=', $params['status']);
        }

        // 分类id
        if (isset($params['cate_id']) && filled($params['cate_id'])) {
            $query->where('cate_id', '=', $params['cate_id']);
        }

        // 标签
        if (isset($params['tag_id']) && $params['tag_id'] > 0) {
            $query->whereHas('productTag', function ($query) use ($params) {
                $query->where('tag_id', $params['tag_id']);
            });
        }

        // 创建时间
        if (isset($params['created_at']) && filled($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) == 2) {
            $query->whereBetween(
                'created_at',
                [ $params['created_at'][0], $params['created_at'][1] ]
            );
        }

        // 更新时间
        if (isset($params['updated_at']) && filled($params['updated_at']) && is_array($params['updated_at']) && count($params['updated_at']) == 2) {
            $query->whereBetween(
                'updated_at',
                [ $params['updated_at'][0], $params['updated_at'][1] ]
            );
        }

        // 删除时间
        if (isset($params['deleted_at']) && filled($params['deleted_at']) && is_array($params['deleted_at']) && count($params['deleted_at']) == 2) {
            $query->whereBetween(
                'deleted_at',
                [ $params['deleted_at'][0], $params['deleted_at'][1] ]
            );
        }

        //sort
        $query->orderBy('id', 'desc');
        if (isset($params['sort']) && in_array($params['sort'], [1, 2])) {
            if ($params['sort'] == 1) {
                //new
                $query->orderBy('created_at', 'desc');
            } else if ($params['sort'] == 2) {
                //hot
                $query->orderBy('click', 'desc');
            }
        }

        return $query;
    }
}