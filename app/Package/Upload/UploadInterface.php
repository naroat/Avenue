<?php


namespace App\Package\Upload;


interface UploadInterface
{
    /**
     * 上传文件
     *
     * @param $file 文件obj
     * @param $path 路径
     * @param $filename 文件名
     * @param $acl 访问权限
     * @return mixed
     */
    public function upload($file, $path, $filename, $acl);
}
