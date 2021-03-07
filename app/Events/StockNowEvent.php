<?php

namespace App\Events;

use App\Entities\StockNow;

class StockNowEvent
{
    /**
     * @var StockNow[]
     */
    public array $entities;

    public function __construct(StockNow ...$entities)
    {
        $this->entities = $entities;
    }
}
