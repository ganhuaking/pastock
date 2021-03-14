<?php

namespace App\Http\Controllers\Line;

use App\OpenIDConnect\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotifyCallback
{
    public function __invoke(Request $request, Manager $manager)
    {
        $client = $manager->createLineNotifyDriver();

        $tokenSet = $client->handleCallback([
            'grant_type' => 'authorization_code',
            'code' => $request->input('code'),
            'redirect_uri' => route('auth.line.callback'),
        ]);

        Log::debug('Token endpoint return: ' . json_encode($tokenSet->toArray()));

        $accessToken = $tokenSet->accessToken();


        $response = Http::withToken($accessToken)->asForm()->post('https://notify-api.line.me/api/notify', [
            'message' => 'Hello world',
        ]);

        Log::debug('Notify body: ' . $response->body());


        $response = Http::withToken($accessToken)->get('https://notify-api.line.me/api/status');

        Log::debug('Status body: ' . $response->body());

        // Revoke
        // Http::withToken($accessToken)->post('https://notify-api.line.me/api/revoke');
    }
}
