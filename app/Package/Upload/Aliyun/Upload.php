<?php


namespace App\Package\Upload\Aliyun;

use OSS\Core\OssException;
use OSS\OssClient;
use App\Package\Upload\UploadInterface;

class Upload implements UploadInterface
{
    protected $accessKeyId;

    protected $accessKeySecret;

    protected $endpoint;

    protected $bucket;

    protected $subDir;

    protected $options = [];

    public function __construct()
    {
        $this->accessKeyId = config('aliyun.oss.access_key_id');
        $this->accessKeySecret = config('aliyun.oss.access_key_secret');
        $this->endpoint = config('aliyun.oss.endpoint');
        $this->bucket = config('aliyun.oss.bucket');
        $this->subDir = config('aliyun.oss.sub_dir');
    }

    /**
     * 阿里oss文件上传
     *
     * @param $file
     * @param $filename
     * @return mixed
     */
    public function upload($file, $path, $filename, $acl)
    {
        try {
            $path = $this->subDir . '/' . $path;
            $object = $path . '/' . $filename;
            //设置头信息
            $this->setOptions($file, [
                'filename' => $filename
            ]);
            //本地创建临时文件
            $tempfile = $this->createTemp($file, $filename);

            //直传
            $this->uploadDirect($object, $tempfile, $acl);

            //清除临时文件
            $this->clearTemp($tempfile);
        } catch (OssException $e) {
            throw new \Exception($e->getMessage());
        }
        //返回路径
        return '/' . $object;
    }

    /**
     * 直接上传
     *
     * @param $object
     * @param $tempfile
     * @param $acl
     * @throws OssException
     */
    public function uploadDirect($object, $tempfile, $acl)
    {
        //实例oss
        $ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
        //上传
        $ossClient->multiuploadFile($this->bucket, $object, $tempfile, $this->options);
        //设置访问权限
        $this->setAcl($ossClient, $object, $acl);
    }

    /**
     * 本地创建临时文件
     *
     * @param $file
     * @param $path
     * @param $filename
     * @return string
     */
    public function createTemp($file, $filename)
    {
        $temppath = 'uploads/tmp/';
        $pathfull = $temppath . $filename;
        $result = $file->moveTo($pathfull);
        return $pathfull;
    }

    /**
     * 清除临时文件
     *
     * @param $tempfile
     */
    public function clearTemp($tempfile)
    {
        @unlink($tempfile);
    }

    /**
     * 设置请求头
     *
     * @param $param
     */
    public function setOptions($file, $param)
    {
        //Content-Type
        $this->options['Content-Type'] = $this->getContentType($file);

        if (isset($param['filename'])) {
            //$this->options[OssClient::OSS_HEADERS]['Content-Disposition'] = 'attachment;filename=' . $param['filename'];
            $this->options[OssClient::OSS_HEADERS]['Content-Disposition'] = 'inline;filename=' . $param['filename'];
        }
    }

    /**
     * 设置访问权限
     *
     * @param $ossClient
     * @param $acl
     * @param $object
     */
    public function setAcl($ossClient, $object, $acl)
    {
        $oss_acl = 'private';
        if ($acl == 'public') {
            $oss_acl = 'public-read';
        }
        $ossClient->putObjectACL($this->bucket, $object, $oss_acl);
    }

    /**
     * 获取content-type
     *
     * @param $file
     * @return string
     */
    public function getContentType($file)
    {
        $mimeType = $file->getMimeType();
        if ($mimeType == 'image/jpeg') {
            $mimeType = 'image/jpg';
        }
        return $mimeType;
    }
}
