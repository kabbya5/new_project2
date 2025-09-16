<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\RecenlyPlayGameController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(GameController::class)->group(function(){
     Route::get('/play/game/{game}', 'playGame');
});

Route::controller(AuthController::class)->group(function(){
    Route::post('/signup', 'register');
    Route::post('/login', 'login');
});

Route::controller(WebhookController::class)->group(function(){
    Route::get('/user/balance', 'balance');
    Route::post('/user/transactioni','transaction');
});

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout', [AuthController::class,'logout']);

    Route::controller(RecenlyPlayGameController::class)->group(function(){
        Route::get('/games/recenly/play','index');
        Route::get('game/play/{id}', 'store');
    });

});

Route::get('/game/categories/index',[CategoryController::class, 'index']);
Route::get('/game/providers/index',[ProviderController::class, 'index']);
Route::get('game/category/providers/{slug}',[ProviderController::class,'categoryProviders']);
Route::get('/games/index',[GameController::class, 'index']);
Route::get('/sliders/index', [SliderController::class, 'index']);

Route::middleware(['auth:sanctum','admin'])->prefix('admin')->group(function(){
    Route::controller(CategoryController::class)->group(function(){
        Route::post('/categories/store', 'store');
        Route::put('/categories/update/{category}', 'update');
    });

    Route::controller(ProviderController::class)->group(function(){
        Route::post('/providers/store', 'store');
        Route::put('/providers/update/{provider}', 'update');
    });

    Route::controller(GameController::class)->group(function(){
        Route::post('/games/store', 'store');
        Route::put('/game/update/{game}', 'update');
    });

    Route::controller(SliderController::class)->group(function(){
        Route::post('sliders/store', 'store');
        Route::put('/sliders/update/{slider}', 'update');
        Route::delete('/sliders/delete/{slider}', 'destroy');
    });
});
