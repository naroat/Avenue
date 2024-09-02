<?php


namespace App\Package;


class Utils
{
    //xss
    public static function setSaveData(array $data)
    {
        foreach ($data as $key => $v) {
            if (is_string($v)) {
                //转换html内容
                $data[$key] = htmlspecialchars($v, ENT_QUOTES);
            } else {
                $data[$key] = $v;
            }
        }
        return $data;
    }

    public static function ormSql($model)
    {
        $bindings = $model->getBindings();
        $sql = str_replace('?', '%s', $model->toSql());
        foreach ($bindings as $key => $val) {
            if (is_string($val)) {
                $bindings[$key] = "'" . $val . "'";
            }
        }
        $tosql = sprintf($sql, ...$bindings);
        return $tosql;
    }
}