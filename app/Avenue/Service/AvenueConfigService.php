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

use App\Avenue\Mapper\AvenueConfigMapper;
use App\Avenue\Model\AvenueConfig;
use App\Package\Utils;
use Mine\Abstracts\AbstractService;

/**
 * 配置管理服务类
 */
class AvenueConfigService extends AbstractService
{
    /**
     * @var AvenueConfigMapper
     */
    public $mapper;

    public function __construct(AvenueConfigMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function getPageList(?array $params = null, bool $isScope = true): array
    {
        $list = $this->mapper->listQuerySetting($params, $isScope)->paginate();

        $list->each(function ($item) {
            if (!empty($item->value)) {
                $item->value = html_entity_decode($item->value);
            }
        });
        return $this->mapper->setPaginate($list);
    }

    public function update(mixed $id, array $data): bool
    {
        $data = Utils::setSaveData($data);
        return $this->mapper->update($id, $data);
    }

    public function getConfigByCode(?array $params = null)
    {
        $data = $this->mapper->getModel()->where('code', $params['code'])->first();
        if (in_array($data->code, AvenueConfig::$htmlTran)) {
            $data->value = html_entity_decode($data->value);
        }
        return $data;
    }
}