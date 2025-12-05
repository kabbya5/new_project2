<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WebhookController;
use App\Services\BrightDataService;
use App\Services\OroPlayService;
use App\Services\Proxy6Service;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/playfiver/webhook', [WebhookController::class, 'handle']);

Route::get('/test', function(OroPlayService $oroPlayService) {
    $response = $oroPlayService->deposite('kabbya12', 500.00, 'WD1234567');
    dd($response);
});


Route::get('/test', function(OroPlayService $oroPlayService) {
  	$data['userCode'] = 'kabbya12';
    $response = $oroPlayService->launch_url($data);
    dd($response);
});