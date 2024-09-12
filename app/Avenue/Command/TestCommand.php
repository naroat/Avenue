<?php

declare(strict_types=1);

namespace App\Avenue\Command;

use App\Avenue\Service\AvenueGoogleAuthService;
use App\Package\Log;
use Google\Task\Composer;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;
use Google;

#[Command]
class TestCommand extends HyperfCommand
{
    public function __construct(protected ContainerInterface $container)
    {
        parent::__construct('test:cmd');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Hyperf Demo Command');
    }

    public function handle()
    {
/*        var_dump(config('google.grant_domain') . '/api/v1/avenue/google/auth/notify');
        exit;*/
//        Log::get()->info('123123');
        $ser = make(AvenueGoogleAuthService::class);
        var_dump($ser->authUrl());
//        https://accounts.google.com/o/oauth2/v2/auth?response_type=code&access_type=online&client_id=12540122333-n9v49it3fhpuetv9usj009a4al3r1c66.apps.googleusercontent.com&redirect_uri=http%3A%2F%2Flocalhost%3A9501%2Fv1%2Favenue%2Fgoogle%2Fauth%2Fnotify&state=9dcdbcf1432c992fdbb0a1afec479cd2&scope=email%20profile&approval_prompt=auto"
        var_dump('text:cmd');
    }
}