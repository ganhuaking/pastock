<?php

namespace App\Stock;

use App\Entities\StockQuery;
use Illuminate\Support\Facades\Http;

/**
 * @see https://www.twse.com.tw/exchangeReport/BWIBBU_d?response=json&date=20170906&selectType=ALL
 * @see https://www.twse.com.tw/exchangeReport/BWIBBU_d?response=html&date=20170906&selectType=ALL
 * @use Http
 */
class Query
{
    public function parse(string $year, string $month, string $day, string $stock): StockQuery
    {
        $response = Http::get(sprintf(
            'http://www.twse.com.tw/exchangeReport/STOCK_DAY?response=json&date=%s%s%s&stockNo=%s',
            $year,
            $month,
            $day,
            $stock
        ));

        return StockQuery::createFromApiV1($stock, $response->json());
    }
}
