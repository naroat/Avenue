<?php

declare(strict_types=1);

namespace App\Avenue\Model;

use Hyperf\Database\Model\SoftDeletes;
use Mine\MineModel;

/**
 * @property int $id ID
 * @property string $title 分类名称
 * @property int $p_id 父id
 * @property int $sort 排序
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property-read null|\Hyperf\Database\Model\Collection|AvenueArticleCategory[] $children 
 */
class AvenueArticleCategory extends MineModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'avenue_article_category';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'title', 'p_id', 'sort', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'p_id' => 'integer', 'sort' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * @return \Hyperf\Database\Model\Relations\HasOne
     */
    public function children()
    {
        return $this->hasMany(AvenueArticleCategory::class, 'p_id', 'id');
    }
}
