<?php


namespace App\Package;


class Utils
{
    //xss
    public static function setSaveData(\Hyperf\Database\Model\Model $model, array $data)
    {
        foreach ($data as $key => $v) {
            if (is_string($v)) {
                //转换html内容
                $model->$key = htmlspecialchars($v, ENT_QUOTES);
            } else {
                $model->$key = $v;
            }
        }
        return $model;
    }
}