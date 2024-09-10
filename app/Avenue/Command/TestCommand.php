<?php

declare(strict_types=1);

namespace App\Avenue\Command;

use Google\Task\Composer;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerInterface;
use Google;
use Psr\Log\LoggerInterface;

#[Command]
class TestCommand extends HyperfCommand
{
    #[Inject]
    public LoggerInterface $logger;

    public function __construct(protected ContainerInterface $container, LoggerFactory $loggerFactory)
    {
        parent::__construct('test:cmd');
        $this->logger = $loggerFactory->get('log11');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Hyperf Demo Command');
    }

    public function handle()
    {
        $this->logger->info('123');
        /*$client = new Google\Client();
        $client->setClientId(config('google.client_id'));
        $client->setScopes('email profile');
        $client->setRedirectUri('https://www.avenue.wang/api/v1/avenue/google/auth/notify');
        $client->setPrompt('consent');
        $url = $client->createAuthUrl();
        var_dump($url);exit;
        var_dump('text:cmd');*/
    }
}