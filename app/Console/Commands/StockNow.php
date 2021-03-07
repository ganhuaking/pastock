<?php

namespace App\Console\Commands;

use App\Entities\StockNow as StockNowEntity;
use App\Events\StockNowEvent;
use App\Stock\Now;
use Illuminate\Console\Command;

/**
 * @see https://mis.twse.com.tw/stock/api/getStockInfo.jsp?ex_ch=tse_2330.tw&json=1&delay=0
 */
class StockNow extends Command
{
    protected $signature = 'stock:now {stocks?*}';

    protected $description = 'Stock Now';

    public function handle(Now $now): int
    {
        $stocks = $this->argument('stocks');

        if (empty($stocks)) {
            $this->output->info('無傳入任何資訊');

            return 0;
        }

        $entities = array_map(function ($stock) use ($now) {
            return $now->parse($stock);
        }, $stocks);

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
        }, $entities);

        $this->table([
            '時間',
            '股票代號',
            '股票簡稱',
            '開盤價',
            '最高價',
            '最低價',
            '收盤價',
        ], $rows);

        event(new StockNowEvent(...$entities));

        return 0;
    }
}
