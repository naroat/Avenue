<?php

declare(strict_types=1);

namespace App\Avenue\Model;

use Mine\MineModel;

/**
 * @property int $id ID
 * @property string $title 名称
 * @property string $textcolor 文本颜色
 * @property string $color 背景颜色
 * @property int $sort 排序
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class AvenueProductTagRealtion extends MineModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'avenue_product_tag_relation';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'product_id', 'tag_id', 'created_at', 'updated_at', 'deleted_at'];

}
