<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * @see https://clu.gitbook.io/python-web-crawler-note/45-tai-wan-zheng-quan-jiao-yi-suo
 */
class StockQuery extends Command
{
    protected $signature = 'stock:query {--year=} {--month=} {stock}';

    protected $description = 'Stock Query';

    public function handle(): int
    {
        $year = $this->option('year') ?? date('Y');
        $month = $this->option('month') ?? date('m');

        $stock = $this->argument('stock');

        $response = Http::get(sprintf(
            'http://www.twse.com.tw/exchangeReport/STOCK_DAY?response=json&date=%s%s01&stockNo=%s',
            $year,
            $month,
            $stock
        ));

        $this->output->title($response->json('title'));

        $this->table($response->json('fields'), $response->json('data'));

        return 0;
    }
}
