<?php

declare(strict_types=1);

namespace App\Avenue\Command;

use App\Avenue\Service\AvenueGoogleAuthService;
use App\Package\Log;
use Firebase\JWT\JWT;
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
        /*$token = 'eyJhbGciOiJSUzI1NiIsImtpZCI6ImQ3YjkzOTc3MWE3ODAwYzQxM2Y5MDA1MTAxMmQ5NzU5ODE5MTZkNzEiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIxMjU0MDEyMjMzMy1uOXY0OWl0M2ZocHVldHY5dXNqMDA5YTRhbDNyMWM2Ni5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsImF1ZCI6IjEyNTQwMTIyMzMzLW45djQ5aXQzZmhwdWV0djl1c2owMDlhNGFsM3IxYzY2LmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwic3ViIjoiMTExNjA1NzM3NzM5NTg4NDQ2NTEyIiwiZW1haWwiOiJ0YW9yYW4xNDAxQGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJhdF9oYXNoIjoiX05HT0NtaDlZRFhMNkN6bnZEZGJSUSIsIm5hbWUiOiJ0YW8gdGFvIiwicGljdHVyZSI6Imh0dHBzOi8vbGgzLmdvb2dsZXVzZXJjb250ZW50LmNvbS9hL0FDZzhvY0k2dmp2WFNwRW9MVHYxMy1Sd0RxOVIxOFR5ZTVuSF9kWE9CeGx5djZwSmdFYjUydz1zOTYtYyIsImdpdmVuX25hbWUiOiJ0YW8iLCJmYW1pbHlfbmFtZSI6InRhbyIsImlhdCI6MTcyNjExMzA2NywiZXhwIjoxNzI2MTE2NjY3fQ.P2_o1bLMrT56F3vUOLu9QU7MXgjGWBqYUSUNvl6avkDeU_wpzw6EYU2tt_y6agbQIRPyKqpUqUWuNro2oxXQmW3FIcsf6O7D-m2rEKGHHvUI2Dbte6W3bvK1paHuFRBn-Bd0Oe2mIhSmygN9dsIdMVtkPW1FoeDVtTSU8mDV3gA4T9EhKdW0nY4hSEMlJy5VbCXIA9boexuRt6WTgT3hnywOfyb14Vko0wEYgXgKE8Ks2jCVxQLUfHEHCQ6Jf8fwf03VuzaDbzsU5iBKPj00OP4yjRuCWUDwP9NnsTuzi_uuNbpkM_9Z5zy_YVC9ufG3UDpg1LZm1gSQ2MzEzBmOVg';
        $data = JWT::decode($token, 'HS256');
        var_dump($data);exit;*/
        $ser = make(AvenueGoogleAuthService::class);
        var_dump($ser->authUrl());
//        var_dump($ser->tokenInfo());
        var_dump('text:cmd');
    }
}