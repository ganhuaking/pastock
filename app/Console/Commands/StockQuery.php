<?php

namespace App\Console\Commands;

use App\Stock\Query;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * @see https://clu.gitbook.io/python-web-crawler-note/45-tai-wan-zheng-quan-jiao-yi-suo
 */
class StockQuery extends Command
{
    protected $signature = 'stock:query {--year=} {--month=} {stock}';

    protected $description = 'Stock Query';

    public function handle(Query $query): int
    {
        $year = $this->option('year') ?? date('Y');
        $month = $this->option('month') ?? date('m');

        $stock = $this->argument('stock');

        $entity = $query->parse($year, $month, $stock);

        $this->table($entity->fields, $entity->data);

        return 0;
    }
}
