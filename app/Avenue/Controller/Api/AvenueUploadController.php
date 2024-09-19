<?php
namespace App\Avenue\Controller\Api;

use App\Package\Verify;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Mine\MineController;
use App\Package\Upload\Upload;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;

#[Controller(prefix: 'v1/avenue/upload')]
class AvenueUploadController extends MineController
{
    #[Inject]
    protected Upload $upload;

    #[Inject]
    protected Verify $verify;

    /**
     * 上传文件
     *
     * @param RequestInterface $request
     * @return mixed
     * @throws \Exception
     */
    #[RequestMapping(path: "upload", methods: "post")]
    public function upload(RequestInterface $request)
    {
        try {
            //接收参数
            $params = $this->verify->requestParams([
                ['type', ""],
            ], $this->request);

            //验证参数
            $this->verify->check(
                $params,
                [
                    "type" => 'required',
                ],
                [
                    "type.required" => "请设置类型！",
                ]
            );

            $upload = new Upload();
            $file = $upload->checkFile();
            $path = $this->updateType($request->input('type') ?? '');
            if (config('upload.drive') == 'aliyun') {
                //alioss
                $finally_path = $upload->toAlioss($file, $path);
            } else {
                //本地
                $finally_path = $upload->toLocal($file, $path);
            }
            return $this->success([
                'path' => $finally_path
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 上传文件by base64
     *
     * @param RequestInterface $request
     * @return mixed
     * @throws \Exception
     */
    public function uploadBase64(RequestInterface $request)
    {
        $upload = new Upload();
        $file_param = $request->all()['file'];
        //生成文件名
        $ext_explode = explode('.', $file_param['_name']);
        $ext = '.' . $ext_explode[count($ext_explode) - 1];
        $filename = $upload->getMsectime() . $ext;
        //上传目标目录
        $path = $this->updateType($request->input('type') ?? '');
        if (config('upload.drive') == 'aliyun') {
            //alioss
            $finally_path = $upload->saveBase64ToAlioss($filename, $file_param['miniurl'], $path);
        } else {
            //本地
            $finally_path = $upload->saveBase64ToLocal($filename, $file_param['miniurl'], $path);
        }
        return $this->success([
            'path' => $finally_path
        ]);
    }

    #[RequestMapping(path: "uploadByUrl", methods: "post")]
    public function uploadByUrl()
    {
        try{
            //接收参数
            $params = $this->verify->requestParams([
                ['file_url', ""],
            ], $this->request);

            //验证参数
            $this->verify->check(
                $params,
                [
                    "file_url" => 'required|url',
                ],
                []
            );

            $upload = new Upload();
            $upload_remote_path = 'avenue/uploads/product/logo/';
            $finally_path = $upload->toOssByUrl($params['file_url'], $upload_remote_path);
            return $this->success([
                'path' => $finally_path
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 获取路径
     *
     * @param $type
     * @return string
     * @throws \Exception
     */
    public function updateType($type)
    {
        switch ($type) {
            case 'product_logo':
                $path = 'public/uploads/product/logo/';
                break;
            default:
                throw new \Exception('上传类型错误！');
                break;
        }
        return $path;
    }
}