```
時間, 股票代號, 股票簡稱, 開盤價, 最高價, 最低價, 收盤價

@foreach($entities as $entity)
{{ $entity->datetime->toDateTimeString() }}, {{ $entity->symbol }}, {{ $entity->name_short }}, {{ sprintf('%.2f', $entity->opening_price) }}, {{ sprintf('%.2f', $entity->maximum_price) }}, {{ sprintf('%.2f', $entity->maximum_price) }}, {{ sprintf('%.2f', $entity->minimum_price) }}, {{ sprintf('%.2f', $entity->closing_price) }}
@endforeach
```