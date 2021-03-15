<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * @see https://www.twse.com.tw/exchangeReport/BWIBBU_d?response=json&date=20170906&selectType=ALL
 * @see https://www.twse.com.tw/exchangeReport/BWIBBU_d?response=html&date=20170906&selectType=ALL
 */
class StockPers extends Command
{
    protected $signature = 'stock:pers
                                {--year= : 查詢年}
                                {--month= : 查詢月}
                                {--day= : 查詢日}
                                {--filter-stock=* : 篩選股票號碼}
                                {--filter-per-gt= : 篩選本益比大於}
                                {--filter-per-lt= : 篩選本益比小於}
                                {--filter-yr-gt= : 篩選殖利率大於}
                                {--filter-yr-lt= : 篩選殖利率小於}
                                {--filter-pbr-gt= : 篩選股價淨值比大於}
                                {--filter-pbr-lt= : 篩選股價淨值比小於}
                                {--sort=0 : 排序，使用數字 index}
                                {--sort-reverse : 反向排序}
                                {--limit= : 限制筆數，預設不限}
                                ';

    protected $description = 'Stock PER/YR/PBR';

    public function handle(): int
    {
        $year = $this->option('year') ?? date('Y');
        $month = $this->option('month') ?? date('m');
        $day = $this->option('day') ?? date('d');

        $filterStock = (int)$this->option('filter-stock');
        $filterPerGt = (int)$this->option('filter-per-gt');
        $filterPerLt = (int)$this->option('filter-per-lt');
        $filterYrGt = (int)$this->option('filter-yr-gt');
        $filterYrLt = (int)$this->option('filter-yr-lt');
        $filterPbrGt = (float)$this->option('filter-pbr-gt');
        $filterPbrLt = (float)$this->option('filter-pbr-lt');
        $sort = $this->option('sort');
        $sortReverse = $this->option('sort-reverse');
        $limit = (int)$this->option('limit');

        if ($filterPbrGt && $filterPbrLt) {
            throw new \RuntimeException('不可同時啟用股價淨值比大於與小於 1');
        }

        $response = Http::get(sprintf(
            'https://www.twse.com.tw/exchangeReport/BWIBBU_d?response=json&date=%s%s%s&selectType=ALL&_=161',
            $year,
            $month,
            $day
        ));

        $this->output->title("{$year} 年 {$month} 月 {$day} 日 個股日本益比、殖利率及股價淨值比");

        $collection = collect($response->json('data'));

        if ($filterStock) {
            $collection = $collection->whereIn(0, $filterStock);
        }

        if ($filterPerGt) {
            $collection = $collection->where(4, '>', $filterPerGt);
        }

        if ($filterPerLt) {
            $collection = $collection->where(4, '<', $filterPerLt);
        }

        if ($filterYrGt) {
            $collection = $collection->where(2, '>', $filterYrGt);
        }

        if ($filterYrLt) {
            $collection = $collection->where(2, '<', $filterYrLt);
        }

        if ($filterPbrGt) {
            $collection = $collection->where(5, '>', $filterPbrGt);
        }

        if ($filterPbrLt) {
            $collection = $collection->where(5, '<', $filterPbrLt);
        }

        if ($sort) {
            if ($sortReverse) {
                $collection = $collection->sortByDesc($sort);
            } else {
                $collection = $collection->sortBy($sort);
            }
        }

        if ($limit) {
            $collection = $collection->take($limit);
        }

        $this->table(
            [
            '股票代號',
            '股票名稱',
            '殖利率',
            '股利年度',
            '本益比',
            '股價淨值比',
            '財報年／季',
            ],
            $collection
        );

        return 0;
    }
}
