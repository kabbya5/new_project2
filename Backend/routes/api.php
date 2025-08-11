<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\ProviderController;
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

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout', [AuthController::class,'logout']);
});

Route::get('/game/categories/index',[CategoryController::class, 'index']);
Route::get('/game/providers/index',[ProviderController::class, 'index']);
Route::get('/games/index',[GameController::class, 'index']);

Route::middleware(['auth:sanctum','admin'])->prefix('admin')->group(function(){
    Route::controller(CategoryController::class)->group(function(){
        Route::post('/categories/store', 'store');
    });

    Route::controller(ProviderController::class)->group(function(){
        Route::post('/providers/store', 'store');
    });

    Route::controller(GameController::class)->group(function(){
        Route::post('/games/store', 'store');
    });
});
