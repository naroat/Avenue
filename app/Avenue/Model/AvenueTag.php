<?php

declare(strict_types=1);

namespace App\Avenue\Model;

use Hyperf\Database\Model\SoftDeletes;
use Mine\MineModel;

/**
 * @property int $id ID
 * @property string $title 名称
 * @property string $textcolor 文本颜色
 * @property string $color 背景颜色
 * @property int $sort 排序
 * @property int $type 标签类型：0产品； 1文章
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class AvenueTag extends MineModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'avenue_tag';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'title', 'textcolor', 'color', 'sort', 'type', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'sort' => 'integer', 'type' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
