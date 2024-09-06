<?php

declare(strict_types=1);

namespace App\Avenue\Model;

use Hyperf\Database\Model\SoftDeletes;
use Mine\MineModel;

/**
 * @property int $id ID
 * @property string $title 名称
 * @property string $desc 描述
 * @property string $logo logo
 * @property string $link 链接
 * @property int $cate_id 分类id
 * @property string $tags 标签,多个','隔开： 如,1,2,3,
 * @property int $sort 排序
 * @property int $click 点击数
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class AvenueProduct extends MineModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'avenue_product';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'title', 'desc', 'logo', 'link', 'cate_id', 'tags', 'status', 'sort', 'click', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'cate_id' => 'integer', 'sort' => 'integer', 'click' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * @return \Hyperf\Database\Model\Relations\HasOne
     */
    public function productCate()
    {
        return $this->hasOne(AvenueProductCategory::class, 'id', 'cate_id');
    }

    /**
     * @return \Hyperf\Database\Model\Relations\BelongsToMany
     */
    public function productTag()
    {
        return $this->belongsToMany(AvenueTag::class, 'avenue_product_tag', 'product_id', 'tag_id');
    }
}
