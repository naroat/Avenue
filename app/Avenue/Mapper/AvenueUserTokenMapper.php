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

use App\Avenue\Model\AvenueTag;
use App\Avenue\Model\AvenueUser;
use App\Avenue\Model\AvenueUserToken;
use Hyperf\Database\Model\Builder;
use Mine\Abstracts\AbstractMapper;

/**
 * 产品标签Mapper类
 */
class AvenueUserTokenMapper extends AbstractMapper
{
    /**
     * @var AvenueTag
     */
    public $model;

    public function assignModel()
    {
        $this->model = AvenueUserToken::class;
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

        return $query;
    }
}