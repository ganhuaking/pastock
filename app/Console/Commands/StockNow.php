<?php

namespace App\Console\Commands;

use App\Entities\StockNow as StockNowEntity;
use App\Events\StockNowEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * @see https://mis.twse.com.tw/stock/api/getStockInfo.jsp?ex_ch=tse_2330.tw&json=1&delay=0
 */
class StockNow extends Command
{
    protected $signature = 'stock:now {stocks*}';

    protected $description = 'Stock Now';

    public function handle(): int
    {
        $stocks = $this->argument('stocks');

        foreach ($stocks as $stock) {
            $this->parse($stock);
        }

        return 0;
    }

    private function parse(string $stock)
    {
        $response = Http::get(sprintf(
            'https://mis.twse.com.tw/stock/api/getStockInfo.jsp?ex_ch=tse_%s.tw&json=1&delay=0',
            $stock
        ));

        $data = $response->json('msgArray')[0];

        $entity = StockNowEntity::createFromApiV1($data);

        $this->output->title(sprintf(
            '%s %s 在 %s 的即時資訊',
            $entity->symbol,
            $entity->name_short,
            $entity->datetime->toDateTimeString()
        ));

        $this->table([
            '開盤價',
            '最高價',
            '最低價',
            '收盤價',
        ], [
            [
                sprintf('%.2f', $entity->opening_price),
                sprintf('%.2f', $entity->maximum_price),
                sprintf('%.2f', $entity->minimum_price),
                sprintf('%.2f', $entity->closing_price),
            ],
        ]);

        event(new StockNowEvent($entity));
    }
}
