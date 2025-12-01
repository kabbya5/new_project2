<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WebhookController;
use App\Services\BrightDataService;
use App\Services\Proxy6Service;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/playfiver/webhook', [WebhookController::class, 'handle']);

Route::get('/test', function(Proxy6Service $proxyService) {
        $proxyService = new Proxy6Service();

    // প্রথমে active proxies নিন
    $proxiesData = $proxyService->getProxies(1);

    if (empty($proxiesData['data'])) {
        return response()->json([
            'success' => false,
            'message' => 'No active proxies found'
        ]);
    }

    // প্রথম প্রোক্সি ব্যবহার
    $proxy = $proxiesData['data'][0];

    // টেস্ট করার জন্য URL (আপনি চাইলে অন্য URL দিতে পারেন)
    $url = 'https://api.ipify.org?format=json'; // এটা আপনার public IP দেখাবে

    $result = $proxyService->fetchUrlViaProxy($url, $proxy);

    return response()->json($result);

});

Route::get('/brightdata-test', function () {
    $result = BrightDataService::test('us'); // বা অন্য country code
    
    if ($result['success']) {
        return response()->json([
            'success' => true,
            'status_code' => $result['status_code'],
            'proxy_country' => $result['proxy_country'],
            'body' => $result['body'],
        ]);
    } else {
        return response()->json([
            'success' => false,
            'status_code' => $result['status_code'] ?? 500,
            'proxy_country' => $result['proxy_country'] ?? null,
            'error' => $result['error'] ?? 'Unknown error',
        ]);
    }
});
