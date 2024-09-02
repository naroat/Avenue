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
 * 产品标签验证数据类
 */
class AvenueTagRequest extends MineFormRequest
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
            //文本颜色 验证
            'textcolor' => 'required',
            //背景颜色 验证
            'color' => 'required',
            //排序 验证
            'sort' => 'required',
            //标签类型：0产品； 1文章 验证
            'type' => 'required',

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
            //文本颜色 验证
            'textcolor' => 'required',
            //背景颜色 验证
            'color' => 'required',
            //排序 验证
            'sort' => 'required',
            //标签类型：0产品； 1文章 验证
            'type' => 'required',

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
            'textcolor' => '文本颜色',
            'color' => '背景颜色',
            'sort' => '排序',
            'type' => '标签类型：0产品； 1文章',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',

        ];
    }

}