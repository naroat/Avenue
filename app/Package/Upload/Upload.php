<?php


namespace App\Package\Upload;

use Hyperf\Di\Annotation\Inject;

use Hyperf\HttpServer\Contract\RequestInterface;


class Upload
{
    #[Inject]
    protected RequestInterface $request;

    /**
     * @var string 上传目录
     */
    protected $uploadTmpDir = 'public/uploads/tmp/';

    /**
     * 上传到本地
     *
     * @param $file
     * @param null $filename
     * @return string
     * @throws \Exception
     */
    public function toLocal($file, $filename = null)
    {
        $this->checkAndCreateTmpDir();
        $filename = $filename ?? $this->genFilename($file);
        $pathfull = $this->uploadTmpDir . $filename;
        $file->moveTo($pathfull);
        if (!$file->isMoved()) {
            throw new \Exception('文件上传失败!');
        }
        return $pathfull;
    }

    /**
     * 上传到alioss
     */
    public function toAlioss($file, $upload_remote_path, $filename = null)
    {
        //检查目录
        $this->checkAndCreateTmpDir();
        //文件名
        $filename = $filename ?? $this->genFilename($file);
        //完整路径
        $path = $this->uploadTmpDir . $filename;
        //先上传本地
        $this->toLocal($file, $filename);
        //上传alioss
        $uploadAli = new \App\Package\Upload\Aliyun\Upload();
        $uploadAli->uploadDirect($upload_remote_path . $filename, $path, 'public');
        $pathfull = config('aliyun.oss.bucket_domain') . '/' . $upload_remote_path . $filename;
        //清除临时文件
        @unlink($path);
        return $pathfull;
    }

    /**
     * 远程图片上传alioss
     */
    public function toOssByUrl($fileurl, $upload_remote_path, $filename = '')
    {
        if (empty($filename)) {
            $filename = $this->genFilenameByUrl($fileurl);
        }
        $tempFile = $this->uploadTmpDir . $filename;
        $img = file_get_contents($fileurl);
        $result = file_put_contents($tempFile, $img);
        if ($result === false) {
            throw new \Exception('上传失败');
        }

        //上传alioss
        $uploadAli = new \App\Package\Upload\Aliyun\Upload();
        $uploadAli->uploadDirect($upload_remote_path . $filename, $tempFile, 'public');
        $pathfull = config('aliyun.oss.bucket_domain') . '/' . $upload_remote_path . $filename;

        @unlink($tempFile);
        return $pathfull;
    }

    /**
     * 保存base64到文件
     *
     * @param $filename
     * @param $base64
     * @param bool $path
     * @return string
     * @throws \Exception
     */
    public function saveBase64ToLocal($filename, $base64, $path = false)
    {
        if ($path) {
            $this->setUploadTmp($path);
        }
        $this->checkAndCreateTmpDir();
        $pathfull = $this->uploadTmpDir . $filename;
        if (!preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)) {
            throw new \Exception('base64信息错误！');
        }
        if (!file_put_contents($pathfull, base64_decode(str_replace($result[1], '', $base64)))) {
            throw new \Exception('上传失败！');
        }
        return $pathfull;
    }

    /**
     * 保存base64到文件
     *
     * @param $filename
     * @param $base64
     * @param $upload_remote_path
     * @return string
     * @throws \OSS\Core\OssException
     */
    public function saveBase64ToAlioss($filename, $base64, $upload_remote_path)
    {
        //上传到本地目录
        $path = $this->saveBase64ToLocal($filename, $base64);
        //上传alioss
        $uploadAli = new \App\Package\Upload\Aliyun\Upload();
        $uploadAli->uploadDirect($upload_remote_path . $filename, $path, 'public');
        $pathfull = config('aliyun.oss.bucket_domain') . '/' . $upload_remote_path . $filename;
        //清除临时文件
        @unlink($path);
        return $pathfull;
    }

    /**
     * 生成文件名称
     *
     * @return string
     */
    public function genFilename($file)
    {
        return $this->getMsectime() . '.' . $file->getExtension();
    }

    /**
     * 生成文件名称 - 通过文件url
     *
     * @param $fileUrl
     * @return string
     */
    public function genFilenameByUrl($fileUrl)
    {
        $urlParse = parse_url($fileUrl);
        $filename = trim($urlParse['path'], '/');
        $fileAna = explode('.', $filename);
        $ext = $fileAna[count($fileAna)-1];
        return $this->getMsectime() . '.' . $ext;
    }

    /**
     * 获取上传时文件存储目录
     *
     * @return string
     */
    public function getUploadTmp()
    {
        return $this->uploadTmpDir;
    }

    /**
     * 设置上传时文件存储目录
     */
    public function setUploadTmp($dir)
    {
        if (!is_dir($dir)) {
            throw new \Exception('无效的目录！');
        }
        $this->uploadTmpDir = $dir;
    }

    /**
     * 检查并创建目录; 可使用setUploadTmp设置目录
     */
    public function checkAndCreateTmpDir()
    {
        if (!is_dir($this->uploadTmpDir)) {
            mkdir($this->uploadTmpDir, 0777, true);
        }
    }

    /**
     * check
     *
     * @return \Hyperf\HttpMessage\Upload\UploadedFile|\Hyperf\HttpMessage\Upload\UploadedFile[]|null
     * @throws \Exception
     */
    public function checkFile()
    {
        if ($this->request->hasFile('file') && $this->request->file('file')->isValid()) {
            $file = $this->request->file('file');
        } else {
            throw new \Exception('文件上传失败!');
        }

        $this->extCheck($this->request->file('file')->getExtension());

        return $file;
    }

    /**
     * 文件格式验证
     *
     * @param $ext  后缀
     * @param null $customExt array 自定义上传后缀
     * @return bool
     * @throws \Exception
     */
    public function extCheck($ext, $customExt = null)
    {
        $ext = strtolower($ext);
        if ($customExt != null) {
            $exts = $customExt;
        } else {
            $exts = ExtGroup::$ext;
        }
        if (!in_array($ext, $exts)) {
            throw new \Exception('不支持的文件类型！');
        }
        return true;
    }

    public function getMsectime()
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return (int)$msectime;
    }
}
