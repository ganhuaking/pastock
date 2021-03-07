<?php

namespace App\Events;

use App\Entities\StockNow;

class StockNowEvent
{
    public function __construct(public StockNow $entity)
    {
    }
}
