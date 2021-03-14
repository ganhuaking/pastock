<?php

namespace App\Http\Controllers\Line;

use App\OpenIDConnect\Manager;
use Illuminate\Support\Facades\Log;

class NotifyAuthorize
{
    public function __invoke(Manager $manager)
    {
        $client = $manager->createLineNotifyDriver();

        $response = $client->createAuthorizeRedirectResponse([
            'response_mode' => 'form_post',
            'response_type' => 'code',
            'redirect_uri' => route('auth.line.callback'),
            'scope' => 'notify',
        ]);

        $uri = $response->getHeaderLine('Location');

        Log::debug('Start to authorize LINE Notify', [
            'uri' => $uri,
        ]);

        return redirect()->away($uri);
    }
}
