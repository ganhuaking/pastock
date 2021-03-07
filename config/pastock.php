<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pastock Config
    |--------------------------------------------------------------------------
    */

    'default_stocks' => [
        '2330',
    ],

    'basic_listener' => [
        App\Listeners\ConsoleOutputListener::class
    ],

    'addition_listener' => [
        // App\Listeners\PostToDiscordListener::class
    ],
];
