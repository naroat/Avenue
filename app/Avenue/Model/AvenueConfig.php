<?php

declare(strict_types=1);

namespace App\Avenue\Model;

use Hyperf\Database\Model\SoftDeletes;
use Mine\MineModel;

/**
 * @property int $id 
 * @property string $code 配置编码
 * @property string $desc 说明
 * @property int $is_show 配置管理是否显示：0否1是
 * @property string $value 配置值
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class AvenueConfig extends MineModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'avenue_config';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'code', 'desc', 'is_show', 'value', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'is_show' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public static $htmlTran = ['about', 'change_log', 'privacy', 'terms'];
}
