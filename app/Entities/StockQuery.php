<?php

namespace App\Entities;

use Carbon\Carbon;
use DateTimeZone;
use JsonSerializable;

/**
 * 股票歷史資訊
 */
class StockQuery implements JsonSerializable
{
    /** @var array 原始資料 */
    public array $origin;

    /** @var string 股票代號 */
    public string $symbol;

    /** @var array */
    public $title;

    /** @var array */
    public $fields;

    /** @var array */
    public $data;

    /** @var array */
    public $notes;

    /**
     * @param string $stock
     * @param array $origin
     * @return static
     */
    public static function createFromApiV1(string $stock, array $origin): self
    {
        $instance = new self();

        $instance->origin = $origin;
        $instance->symbol = $stock;
        $instance->title = $origin['title'];
        $instance->fields = $origin['fields'];
        $instance->data = $origin['data'];
        $instance->notes = $origin['notes'];

        return $instance;
    }

    public function jsonSerialize()
    {
        return (array)$this;
    }

    /**
     * @return array
     */
    public function origin(): array
    {
        return $this->origin;
    }
}
