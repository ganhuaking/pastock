<?php

namespace App\Listeners;

use App\Events\StockNowEvent;
use Illuminate\Contracts\View\View;
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

        /** @var View $view */
        $view = view('discord.stock_now', ['entities' => $event->entities]);

        Http::post($discordWebhook, [
            'username' => 'Pastock',
            'content' => $view->render(),
        ]);
    }
}
