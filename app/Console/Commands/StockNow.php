<?php

namespace App\Console\Commands;

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
            $stocks = config('pastock.default_stocks');
        }

        $entities = array_map(function ($stock) use ($now) {
            return $now->parse($stock);
        }, $stocks);

        if ($this->output->isVeryVerbose()) {
            dump(...$entities);
        }

        event(new StockNowEvent($this, ...$entities));

        return 0;
    }
}
