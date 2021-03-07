<?php

namespace App\Events;

use App\Entities\StockNow;
use Illuminate\Console\Command;

class StockNowEvent
{
    /**
     * @var Command
     */
    public Command $command;

    /**
     * @var StockNow[]
     */
    public array $entities;

    public function __construct(Command $command, StockNow ...$entities)
    {
        $this->command = $command;
        $this->entities = $entities;
    }
}
