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
 * 配置管理验证数据类
 */
class AvenueConfigRequest extends MineFormRequest
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

        ];
    }
    /**
     * 更新数据验证规则
     * return array
     */
    public function updateRules(): array
    {
        return [
            //说明 验证
            'desc' => 'required',
            //配置管理是否显示：0否1是 验证
            'is_show' => 'required',
            //配置值 验证
            'value' => 'required',

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
            'code' => '配置编码',
            'desc' => '说明',
            'is_show' => '配置管理是否显示：0否1是',
            'value' => '配置值',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',

        ];
    }

}