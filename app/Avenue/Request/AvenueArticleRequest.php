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
 * 文章管理验证数据类
 */
class AvenueArticleRequest extends MineFormRequest
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
            //名称 验证
            'title' => 'required',
            //描述 验证
            'desc' => 'required',
            //内容 验证
            'content' => 'required',
            //分类id 验证
            'cate_id' => 'required',

        ];
    }
    /**
     * 更新数据验证规则
     * return array
     */
    public function updateRules(): array
    {
        return [
            //名称 验证
            'title' => 'required',
            //描述 验证
            'desc' => 'required',
            //内容 验证
            'content' => 'required',
            //分类id 验证
            'cate_id' => 'required',

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
            'title' => '名称',
            'desc' => '描述',
            'content' => '内容',
            'cate_id' => '分类id',
            'click' => '点击数',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',

        ];
    }

}