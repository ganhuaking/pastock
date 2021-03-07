<?php

namespace App\Events;

class StockNowEvent
{
    public function __construct(public array $data)
    {
    }
}
