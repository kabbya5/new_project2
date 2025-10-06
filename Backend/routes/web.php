<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WebhookController;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/playfiver/webhook', [WebhookController::class, 'handle']);
