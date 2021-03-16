<?php

namespace App\Stock;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

/**
 * @see https://www.twse.com.tw/exchangeReport/BWIBBU_d?response=json&date=20170906&selectType=ALL
 * @see https://www.twse.com.tw/exchangeReport/BWIBBU_d?response=html&date=20170906&selectType=ALL
 * @use Http
 */
class Per
{
    /**
     * @param string $year
     * @param string $month
     * @param string $day
     * @return Collection
     */
    public function build(string $year, string $month, string $day): Collection
    {
        $response = Http::get(sprintf(
            'https://www.twse.com.tw/exchangeReport/BWIBBU_d?response=json&date=%s%s%s&selectType=ALL&_=161',
            $year,
            $month,
            $day
        ));

        return collect($response->json('data'));
    }
}
