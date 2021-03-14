<?php


namespace App\OpenIDConnect;


use Illuminate\Support\Manager as BaseManager;
use MilesChou\Psr\Http\Client\HttpClientInterface;
use OpenIDConnect\Client;
use OpenIDConnect\Config;
use OpenIDConnect\Metadata\ClientMetadata;
use OpenIDConnect\Metadata\ProviderMetadata;

class Manager extends BaseManager
{
    public function getDefaultDriver()
    {
        throw new \LogicException('No default driver');
    }

    public function createLineNotifyDriver()
    {
        $provider = new ProviderMetadata([
            'authorization_endpoint' => 'https://notify-bot.line.me/oauth/authorize',
            'token_endpoint' => 'https://notify-bot.line.me/oauth/token',
        ]);

        $config = new Config(
            $provider,
            new ClientMetadata([
                'client_id' => config('openid_connect.client.line_notify.id'),
                'client_secret' => config('openid_connect.client.line_notify.secret'),
            ])
        );

        return new Client($config, $this->container->make(HttpClientInterface::class));
    }
}