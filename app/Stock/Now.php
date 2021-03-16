<?php

namespace App\Stock;

use App\Entities\StockNow;
use App\Entities\StockNow as StockNowEntity;
use Illuminate\Support\Facades\Http;

/**
 * @see https://mis.twse.com.tw/stock/api/getStockInfo.jsp?ex_ch=tse_2330.tw
 * @use Http
 */
class Now
{
    public function parse(string $symbol): StockNow
    {
        $response = Http::get(sprintf(
            'https://mis.twse.com.tw/stock/api/getStockInfo.jsp?ex_ch=tse_%s.tw',
            $symbol
        ));

        $data = $response->json('msgArray')[0];

        return StockNowEntity::createFromApiV1($data);
    }
}
