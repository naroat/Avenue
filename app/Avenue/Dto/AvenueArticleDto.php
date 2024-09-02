<?php
namespace App\Avenue\Dto;

use Mine\Interfaces\MineModelExcel;
use Mine\Annotation\ExcelData;
use Mine\Annotation\ExcelProperty;

/**
 * 文章管理Dto （导入导出）
 */
#[ExcelData]
class AvenueArticleDto implements MineModelExcel
{
    #[ExcelProperty(value: "ID", index: 0)]
    public string $id;

    #[ExcelProperty(value: "名称", index: 1)]
    public string $title;

    #[ExcelProperty(value: "描述", index: 2)]
    public string $desc;

    #[ExcelProperty(value: "内容", index: 3)]
    public string $content;

    #[ExcelProperty(value: "分类id", index: 4)]
    public string $cate_id;

    #[ExcelProperty(value: "点击数", index: 5)]
    public string $click;

    #[ExcelProperty(value: "创建时间", index: 6)]
    public string $created_at;

    #[ExcelProperty(value: "更新时间", index: 7)]
    public string $updated_at;

    #[ExcelProperty(value: "删除时间", index: 8)]
    public string $deleted_at;


}