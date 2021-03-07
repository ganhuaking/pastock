<?php

namespace App\Listeners;

use App\Entities\StockNow as StockNowEntity;
use App\Events\StockNowEvent;

class ConsoleOutputListener
{
    public function __invoke(StockNowEvent $event)
    {
        $rows = array_map(function (StockNowEntity $entity) {
            return [
                $entity->datetime->toDateTimeString(),
                $entity->symbol,
                $entity->name_short,
                sprintf('%.2f', $entity->opening_price),
                sprintf('%.2f', $entity->maximum_price),
                sprintf('%.2f', $entity->minimum_price),
                sprintf('%.2f', $entity->closing_price),
            ];
        }, $event->entities);

        $event->command->table([
            '時間',
            '股票代號',
            '股票簡稱',
            '開盤價',
            '最高價',
            '最低價',
            '收盤價',
        ], $rows);
    }
}
