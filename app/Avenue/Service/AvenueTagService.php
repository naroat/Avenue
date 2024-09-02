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

namespace App\Avenue\Service;

use App\Avenue\Mapper\AvenueTagMapper;
use Mine\Abstracts\AbstractService;

/**
 * 标签服务类
 */
class AvenueTagService extends AbstractService
{
    /**
     * @var AvenueTagMapper
     */
    public $mapper;

    public function __construct(AvenueTagMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function getPageList(?array $params = null, bool $isScope = true): array
    {
        $list = $this->mapper->listQuerySetting($params, $isScope);

        $flag = false;
        if (!empty($params['onlyMenu'])) {
            $list = $list->get();
        } else {
            $flag = true;
            $list = $list->paginate();
        }

        return $flag ? $this->mapper->setPaginate($list) : $list->toArray();
    }
}