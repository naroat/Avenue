<?php
namespace App\Avenue\Dto;

use Mine\Interfaces\MineModelExcel;
use Mine\Annotation\ExcelData;
use Mine\Annotation\ExcelProperty;

/**
 * 产品管理Dto （导入导出）
 */
#[ExcelData]
class AvenueProductDto implements MineModelExcel
{
    #[ExcelProperty(value: "ID", index: 0)]
    public string $id;

    #[ExcelProperty(value: "名称", index: 1)]
    public string $title;

    #[ExcelProperty(value: "描述", index: 2)]
    public string $desc;

    #[ExcelProperty(value: "logo", index: 3)]
    public string $logo;

    #[ExcelProperty(value: "链接", index: 4)]
    public string $link;

    #[ExcelProperty(value: "分类id", index: 5)]
    public string $cate_id;

    #[ExcelProperty(value: "标签,多个','隔开： 如,1,2,3,", index: 6)]
    public string $tags;

    #[ExcelProperty(value: "排序", index: 7)]
    public string $sort;

    #[ExcelProperty(value: "点击数", index: 8)]
    public string $click;

    #[ExcelProperty(value: "创建时间", index: 9)]
    public string $created_at;

    #[ExcelProperty(value: "更新时间", index: 10)]
    public string $updated_at;

    #[ExcelProperty(value: "删除时间", index: 11)]
    public string $deleted_at;


}