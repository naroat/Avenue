<?php
namespace App\Avenue\Dto;

use Mine\Interfaces\MineModelExcel;
use Mine\Annotation\ExcelData;
use Mine\Annotation\ExcelProperty;

/**
 * 配置管理Dto （导入导出）
 */
#[ExcelData]
class AvenueConfigDto implements MineModelExcel
{
    #[ExcelProperty(value: "ID", index: 0)]
    public string $id;

    #[ExcelProperty(value: "配置编码", index: 1)]
    public string $code;

    #[ExcelProperty(value: "说明", index: 2)]
    public string $desc;

    #[ExcelProperty(value: "配置管理是否显示：0否1是", index: 3)]
    public string $is_show;

    #[ExcelProperty(value: "配置值", index: 4)]
    public string $value;

    #[ExcelProperty(value: "创建时间", index: 5)]
    public string $created_at;

    #[ExcelProperty(value: "更新时间", index: 6)]
    public string $updated_at;

    #[ExcelProperty(value: "删除时间", index: 7)]
    public string $deleted_at;


}