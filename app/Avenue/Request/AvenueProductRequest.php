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
 * 产品管理验证数据类
 */
class AvenueProductRequest extends MineFormRequest
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
            //logo 验证
            'logo' => 'required|url',
            //链接 验证
            'link' => 'required|url',
            //分类id 验证
            'cate_id' => 'required',
            //标签,多个','隔开： 如,1,2,3, 验证
            'tags' => 'required',
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
            //名称 验证
            'title' => 'required',
            //描述 验证
            'desc' => 'required',
            //logo 验证
            'logo' => 'required|url',
            //链接 验证
            'link' => 'required|url',
            //分类id 验证
            'cate_id' => 'required',
            //标签,多个','隔开： 如,1,2,3, 验证
            'tags' => 'required',
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
            'title' => '名称',
            'desc' => '描述',
            'logo' => 'logo',
            'link' => '链接',
            'cate_id' => '分类id',
            'tags' => '标签,多个','隔开： 如,1,2,3,',
            'sort' => '排序',
            'click' => '点击数',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',

        ];
    }

}