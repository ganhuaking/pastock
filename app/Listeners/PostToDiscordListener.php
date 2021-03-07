<?php

namespace App\Listeners;

use App\Events\StockNowEvent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PostToDiscordListener
{
    public function __invoke(StockNowEvent $event)
    {
        $discordWebhook = config('discord.webhook_url');

        Log::debug('Send to Discord', [
            'url' => $discordWebhook,
        ]);

        Http::post($discordWebhook, [
            'username' => 'Pastock',
            'content' => json_encode($event->entity),
        ]);
    }
}
