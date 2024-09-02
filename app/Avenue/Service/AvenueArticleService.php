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

use App\Avenue\Mapper\AvenueArticleMapper;
use Mine\Abstracts\AbstractService;

/**
 * 文章管理服务类
 */
class AvenueArticleService extends AbstractService
{
    /**
     * @var AvenueArticleMapper
     */
    public $mapper;

    public function __construct(AvenueArticleMapper $mapper)
    {
        $this->mapper = $mapper;
    }
}