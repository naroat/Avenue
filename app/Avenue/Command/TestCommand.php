<?php

declare(strict_types=1);

namespace App\Avenue\Command;

use Google\Task\Composer;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
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
        $client = new Google\Client();
        $client->setClientId(config('google.client_id'));
        $client->setScopes('email profile');
        $client->setRedirectUri('http://localhost:9501/v1/avenue/google/auth/notify');//
        $client->setPrompt('consent');
        $url = $client->createAuthUrl();
        var_dump($url);exit;
        var_dump('text:cmd');
    }
}