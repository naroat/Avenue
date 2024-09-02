<?php
namespace App\Avenue\Dto;

use Mine\Interfaces\MineModelExcel;
use Mine\Annotation\ExcelData;
use Mine\Annotation\ExcelProperty;

/**
 * 产品标签Dto （导入导出）
 */
#[ExcelData]
class AvenueTagDto implements MineModelExcel
{
    #[ExcelProperty(value: "ID", index: 0)]
    public string $id;

    #[ExcelProperty(value: "名称", index: 1)]
    public string $title;

    #[ExcelProperty(value: "文本颜色", index: 2)]
    public string $textcolor;

    #[ExcelProperty(value: "背景颜色", index: 3)]
    public string $color;

    #[ExcelProperty(value: "排序", index: 4)]
    public string $sort;

    #[ExcelProperty(value: "标签类型：0产品； 1文章", index: 5)]
    public string $type;

    #[ExcelProperty(value: "创建时间", index: 6)]
    public string $created_at;

    #[ExcelProperty(value: "更新时间", index: 7)]
    public string $updated_at;

    #[ExcelProperty(value: "删除时间", index: 8)]
    public string $deleted_at;


}