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

use App\Avenue\Mapper\AvenueProductMapper;
use App\Avenue\Model\AvenueProductTag;
use App\Package\Utils;
use Hyperf\DbConnection\Annotation\Transactional;
use Mine\Abstracts\AbstractService;

/**
 * 产品管理服务类
 */
class AvenueProductService extends AbstractService
{
    /**
     * @var AvenueProductMapper
     */
    public $mapper;

    public function __construct(AvenueProductMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function getPageList(?array $params = null, bool $isScope = true): array
    {
        $list = $this->mapper->listQuerySetting($params, $isScope)
            ->with('productCate')
            ->with('productTag')
            ->paginate($params['limit'] ?? 15);
        $list->each(function ($item) {
            $item->tags = array_column($item->productTag->toArray(), 'id');
            $item->cate_text = $item->productCate->title ?? '';
        });
        return $this->mapper->setPaginate($list);
    }

    #[Transactional]
    public function save(array $data): mixed
    {
        $tags = $data['tags'];
        unset($data['tags']);
        $productId = $this->mapper->save($data);
        $this->saveTag($productId, $tags);
        return true;
    }

    #[Transactional]
    public function update(mixed $id, array $data): bool
    {
        $tags = $data['tags'];
        unset($data['tags']);
        $this->mapper->update($id, $data);
        $this->saveTag($id, $tags);
        return true;
    }

    public function saveTag($productId, $tags)
    {
        $saveData = [];
        foreach ($tags as $tagId) {
            $saveData[] = [
                'product_id' => $productId,
                'tag_id' => $tagId,
            ];
        }
        $productTagModel = make(AvenueProductTag::class);
        $productTagModel->where('product_id', $productId)->delete();
        $productTagModel->insert($saveData);
    }

    public function addClick($id)
    {
        $data = $this->mapper->read($id);
        if ($data) {
            $data->click++;
            $data->save();
        }
    }
}