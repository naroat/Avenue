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
namespace App\Avenue\Request;

use Mine\MineFormRequest;

/**
 * 产品分类验证数据类
 */
class AvenueProductCategoryRequest extends MineFormRequest
{
    /**
     * 公共规则
     */
    public function commonRules(): array
    {
        return [];
    }

    
    /**
     * 新增数据验证规则
     * return array
     */
    public function saveRules(): array
    {
        return [
            //分类名称 验证
            'title' => 'required',
            //排序 验证
            'sort' => 'required',

        ];
    }
    /**
     * 更新数据验证规则
     * return array
     */
    public function updateRules(): array
    {
        return [
            //分类名称 验证
            'title' => 'required',
            //排序 验证
            'sort' => 'required',

        ];
    }

    
    /**
     * 字段映射名称
     * return array
     */
    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'title' => '分类名称',
            'sort' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',

        ];
    }

}