<?php

namespace App\Console\Commands;

use App\Stock\Per;
use App\Stock\Query;
use Illuminate\Console\Command;
use RuntimeException;

/**
 * @see https://www.twse.com.tw/exchangeReport/BWIBBU_d?response=json&date=20170906&selectType=ALL
 * @see https://www.twse.com.tw/exchangeReport/BWIBBU_d?response=html&date=20170906&selectType=ALL
 */
class StockPer extends Command
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
                                {--price : 股價，會查另一個 API，比較慢}
                                ';

    protected $description = 'Stock PER/YR/PBR';

    public function handle(Per $per, Query $query): int
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
            throw new RuntimeException('不可同時啟用股價淨值比大於與小於 1');
        }

        $this->output->title("{$year} 年 {$month} 月 {$day} 日 個股日本益比、殖利率及股價淨值比");

        $collection = $per->build($year, $month, $day);

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

        if ($limit) {
            $collection = $collection->take($limit);
        }

        $fields = [
            '股票代號',
            '股票名稱',
            '殖利率',
            '股利年度',
            '本益比',
            '股價淨值比',
            '財報年／季',
        ];

        if ($this->option('price')) {
            $fields[] = '股價';

            $collection = $collection->map(function ($value) use ($year, $month, $day, $query) {
                if ($this->output->isVeryVerbose()) {
                    $this->info("Parse stock {$value[0]}");
                }

                usleep(100000);

                $query = $query->parse($year, $month, $day, $value[0]);

                $value[] = collect($query->data)->last()[6];

                return $value;
            });
        }

        if ($sort) {
            if ($sortReverse) {
                $collection = $collection->sortByDesc($sort);
            } else {
                $collection = $collection->sortBy($sort);
            }
        }

        $this->table($fields, $collection);

        return 0;
    }
}
