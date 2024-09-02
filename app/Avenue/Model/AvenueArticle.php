<?php

declare(strict_types=1);

namespace App\Avenue\Model;

use Hyperf\Database\Model\SoftDeletes;
use Mine\MineModel;

/**
 * @property int $id ID
 * @property string $title 名称
 * @property string $desc 描述
 * @property string $content 内容
 * @property int $cate_id 分类id
 * @property int $click 点击数
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class AvenueArticle extends MineModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'avenue_article';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'title', 'desc', 'content', 'cate_id', 'click', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'cate_id' => 'integer', 'click' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
