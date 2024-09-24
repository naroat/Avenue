<?php

declare(strict_types=1);

namespace App\Avenue\Command;

use App\Avenue\Service\AvenueGoogleAuthService;
use App\Package\Log;
use App\Package\Upload\Upload;
use App\Package\Utils;
use Firebase\JWT\JWT;
use Google\Task\Composer;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;
use Google;
use Symfony\Component\Console\Input\InputArgument;

#[Command]
class ImgSaveByUrlCommand extends HyperfCommand
{
    public function __construct(protected ContainerInterface $container)
    {
        parent::__construct('img:save_by_url');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('通过图片url保存logo到oss/服务器， ');
        $this->addArgument('url', InputArgument::OPTIONAL, '图片url');
    }
//17267403918851.ico
    public function handle()
    {
        //获取参数
        $params = $this->input->getArguments();
        //功能
        $imgUrl = $params['url'];
        $upload = new Upload();
        $upload_remote_path = 'avenue/product/logo/';
        $finally_path = $upload->toOssByUrl($imgUrl, $upload_remote_path);
        var_dump($finally_path);
    }
}