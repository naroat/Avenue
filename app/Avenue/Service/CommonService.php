<?php


namespace App\Avenue\Service;


class CommonService
{
    public static function buildPaginate($list)
    {
        return [
            'items' => $list->items(),
            'pageInfo' => [
                'currentPage' => $list->currentPage(),
                'total' => $list->total(),
                'totalPage' => $list->lastPage(),
            ]
        ];
    }
}