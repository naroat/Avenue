<?php

declare(strict_types=1);

namespace App\Avenue\Model;

use Hyperf\Database\Model\SoftDeletes;
use Mine\MineModel;

class AvenueUser extends MineModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'avenue_user';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'nick_name', 'avatar', 'email', 'status'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [];
}
