<?php
namespace App\Avenue\Dto;

use Mine\Interfaces\MineModelExcel;
use Mine\Annotation\ExcelData;
use Mine\Annotation\ExcelProperty;

/**
 * 产品分类Dto （导入导出）
 */
#[ExcelData]
class AvenueProductCategoryDto implements MineModelExcel
{
    #[ExcelProperty(value: "ID", index: 0)]
    public string $id;

    #[ExcelProperty(value: "分类名称", index: 1)]
    public string $title;

    #[ExcelProperty(value: "排序", index: 2)]
    public string $sort;

    #[ExcelProperty(value: "创建时间", index: 3)]
    public string $created_at;

    #[ExcelProperty(value: "更新时间", index: 4)]
    public string $updated_at;

    #[ExcelProperty(value: "删除时间", index: 5)]
    public string $deleted_at;


}