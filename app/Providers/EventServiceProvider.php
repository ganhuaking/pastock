<?php

namespace App\Providers;

use App\Events\StockNowEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        collect(config('pastock.basic_listener'))->each(function ($listener) {
            Event::listen(StockNowEvent::class, $listener);
        });

        collect(config('pastock.addition_listener'))->each(function ($listener) {
            Event::listen(StockNowEvent::class, $listener);
        });
    }
}
