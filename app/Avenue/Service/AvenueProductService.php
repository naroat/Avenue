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
        if ($this->mapper->getModel()->where('link', $data['link'])->exists()) {
            throw new \Exception('产品已存在');
        }
        $tags = $data['tags'];
        unset($data['tags']);
        $productId = $this->mapper->save($data);
        $this->saveTag($productId, $tags);
        return true;
    }

    #[Transactional]
    public function update(mixed $id, array $data): bool
    {
        if ($this->mapper->getModel()->where('link', $data['link'])->where('id', '<>', $id)->exists()) {
            throw new \Exception('产品已存在');
        }
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

    public function fetchProduct($params)
    {
        $url = $params['url'] ?? '';

        // 正则表达式匹配域名
        // 允许字母、数字、连字符，不以连字符开头或结尾，总长度不超过253个字符（包括点）
        // 每个标签（label）长度在1到63个字符之间
        // 这里假设顶级域名（TLD）至少有两个字符，例如.com, .org等
        // $domainRegex = '/^(?![0-9]+$)(?!-)[a-zA-Z0-9-]{1,63}(?<!-)(\.[a-zA-Z0-9-]{1,63}(?<!-))*$/';

        // 去除前后空格，并转换为小写进行匹配（可选，取决于你是否需要区分大小写）
        $trimmedInput = trim(strtolower($url));

        // 使用preg_match进行正则匹配
        /*if (!preg_match($domainRegex, $trimmedInput)) {
            throw new \Exception('无效的域名或地址');
        }*/

        //获取网站信息
        $data = [
            'keywords' => '',
            'desc' => '',
        ];
        $html = Utils::httpRequest($trimmedInput);
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $metas = $dom->getElementsByTagName('meta');
        $links = $dom->getElementsByTagName('link');
        $titles = $dom->getElementsByTagName('title');

        //meta
        for ($i = 0; $i < $metas->length; $i++) {
            $meta = $metas->item($i);
            if ($meta->getAttribute('name') == 'description') {
                $data['desc'] = $meta->getAttribute('content');
            }
            if ($meta->getAttribute('name') == 'keywords') {
                $data['keywords'] = $meta->getAttribute('content');
            }
        }

        //logo
        $data['logo'] = trim($url, '/') . '/favicon.ico';
        if (!getimagesize($data['logo'])) {
            for ($i = 0; $i < $links->length; $i++) {
                $link = $links->item($i);
                if ($link->getAttribute('rel') != 'shortcut icon') {
                    continue;
                }
                $data['logo'] = $link->getAttribute('href');
                //相对路径转换
                if (strpos($data['logo'], '//') !== 0 && strpos($data['logo'], 'http') !== 0) {
                    $data['logo'] = trim($url, '/') . $data['logo'];
                }
            }
        }

        //title
        for ($i = 0; $i < $titles->length; $i++) {
            $title = $titles->item($i);
            $data['title'] = $title->textContent;
        }

        return $data;
    }
}