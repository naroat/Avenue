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

use App\Avenue\Model\AvenueConfig;
use Hyperf\Database\Model\Builder;
use Mine\Abstracts\AbstractMapper;

/**
 * 配置管理Mapper类
 */
class AvenueConfigMapper extends AbstractMapper
{
    /**
     * @var AvenueConfig
     */
    public $model;

    public function assignModel()
    {
        $this->model = AvenueConfig::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        $query->where('is_show', 1);

        // ID
        if (isset($params['id']) && filled($params['id'])) {
            $query->where('id', '=', $params['id']);
        }

        // 配置编码
        if (isset($params['code']) && filled($params['code'])) {
            $query->where('code', 'like', '%'.$params['code'].'%');
        }

        // 说明
        if (isset($params['desc']) && filled($params['desc'])) {
            $query->where('desc', 'like', '%'.$params['desc'].'%');
        }

        // 配置管理是否显示：0否1是
        if (isset($params['is_show']) && filled($params['is_show'])) {
            $query->where('is_show', '=', $params['is_show']);
        }

        // 配置值
        if (isset($params['value']) && filled($params['value'])) {
            $query->where('value', '=', $params['value']);
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

        return $query;
    }
}