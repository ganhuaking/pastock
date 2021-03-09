<?php

namespace App\Entities;

use Carbon\Carbon;
use DateTimeZone;
use JsonSerializable;

/**
 * 股票即時資訊
 *
 */
class StockNow implements JsonSerializable
{
    /** @var array 原始資料 */
    public array $origin;

    /** @var string 股票代號 */
    public string $symbol;

    /** @var string 股票名稱 */
    public string $name;

    /** @var string 股票名稱縮寫 */
    public string $name_short;

    /** @var Carbon 日期 */
    public Carbon $datetime;

    /** @var float 開盤價 */
    public float $opening_price;

    /** @var float 最高價 */
    public float $maximum_price;

    /** @var float 最低價 */
    public float $minimum_price;

    /** @var float 收盤價 */
    public float $closing_price;

    /** @var float 漲停 */
    public float $limit_up;

    /** @var float 跌停 */
    public float $limit_down;

    /** @var float 昨日收盤價 */
    public float $yesterday;

    /**
     * 由 API 取得的資料轉換成 entity
     * @param array $data
     * @return static
     */
    public static function createFromApiV1(array $data): self
    {
        $instance = new self();

        $instance->origin = $data;

        // 基本資料
        $instance->symbol = $data['c'];
        $instance->name_short = $data['n'];

        // ot 為收盤時間，t 為當下時間
        $time = $data['ot'] ?? $data['t'];

        $instance->datetime = Carbon::createFromFormat(
            'Ymd H:i:s',
            "{$data['d']} {$time}",
            new DateTimeZone('Asia/Taipei')
        );

        // 價格
        $instance->opening_price = (float)$data['o'];
        $instance->maximum_price = (float)$data['h'];
        $instance->minimum_price = (float)$data['l'];
        $instance->closing_price = (float)$data['z'];

        // 漲停跌停
        $instance->limit_up = (float)$data['u'];
        $instance->limit_down = (float)$data['w'];

        // 五檔 - 賣
        // $instance->a = (float)$data['a'];
        // $instance->f = (float)$data['f'];
        // 五檔 - 買
        // $instance->b = (float)$data['b'];
        // $instance->g = (float)$data['g'];

        // 總量
        // $instance->v = (float)$data['v'];

        // 昨日收盤價
        $instance->yesterday = (float)$data['y'];

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
