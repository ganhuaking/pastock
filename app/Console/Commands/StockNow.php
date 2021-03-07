<?php

namespace App\Console\Commands;

use App\Events\StockNowEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * @see https://mis.twse.com.tw/stock/api/getStockInfo.jsp?ex_ch=tse_2330.tw&json=1&delay=0
 */
class StockNow extends Command
{
    protected $signature = 'stock:now {stock}';

    protected $description = 'Stock Now';

    public function handle(): int
    {
        $stock = $this->argument('stock');

        $response = Http::get(sprintf(
            'https://mis.twse.com.tw/stock/api/getStockInfo.jsp?ex_ch=tse_%s.tw&json=1&delay=0',
            $stock
        ));


        $data = $response->json('msgArray')[0];

        $this->output->title("{$data['c']} {$data['n']} 在 {$data['d']} {$data['ot']} 的即時資訊");

        $this->table([
            '開盤價',
            '最高價',
            '最低價',
            '收盤價',
        ], [[
            sprintf('%.2f', $data['o']),
            sprintf('%.2f', $data['h']),
            sprintf('%.2f', $data['l']),
            sprintf('%.2f', $data['z']),
        ]]);

        event(new StockNowEvent($data));

        return 0;
    }
}
