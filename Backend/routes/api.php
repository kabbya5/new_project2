<?php

use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\LabelController;
use App\Http\Controllers\Api\ManualyTransactionController;
use App\Http\Controllers\Api\MovingTextController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\RecenlyPlayGameController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\TransactionNumberController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\UserMangeController;

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

Route::get('marquee', [MovingTextController::class, 'index']);

Route::controller(TransactionController::class)->group(function(){
    Route::post('/deposite/notification/', 'depositeNotification')->name('deposite.notification');
    Route::post('/withdraw/notification/', 'withdrawNotification')->name('withdraw.notification');
});

Route::controller(WebhookController::class)->group(function(){
    Route::get('/user/balance', 'balance');
    Route::post('/user/transaction','transaction');
});

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout', [AuthController::class,'logout']);
    Route::post('/me', [AuthController::class,'me']);
    Route::post('/update/profile', [AuthController::class,'updateProfile']);
    Route::post('/update/password', [AuthController::class,'changePassword']);

    Route::controller(RecenlyPlayGameController::class)->group(function(){
        Route::get('/games/recenly/play','index');
        Route::get('game/play/{id}', 'store');
        Route::get('/game/sports-play', 'playSports');
    });

    Route::controller(TransactionController::class)->group(function(){
        Route::post('/deposite', 'depostie');
        Route::post('/withdrow', 'withdrow');
        Route::get('/user/take/bonus/{bonus}', 'takeBonus');
        Route::post('/transactions/store', 'store');
        Route::put('/transactions/update/{transaction}', 'update');
        Route::delete('/admin/transactions/delete/{transaction}', 'delete');
        Route::post('/transactions/approval/{transaction}', 'approval');
    });

    Route::controller(TransactionController::class)->group(function(){
        Route::get('/transactions', 'adminTransactions');
    });

    Route::get('/betting/records',[GameController::class,'gameRecords']);

    Route::get('/all/users', [AdminDashboardController::class, 'allUser']);

    Route::controller(ManualyTransactionController::class)->group(function(){
        Route::post('/manualy/deposite', 'deposite_request');
        Route::post('/manualy/deposit/store', 'store');
        Route::post('/manualy/withdrow/store', 'withdrawStore');
    });
});

Route::get('/game/categories/index',[CategoryController::class, 'index']);
Route::get('/game/providers/index',[ProviderController::class, 'index']);
Route::get('game/category/providers/{slug}',[ProviderController::class,'categoryProviders']);
Route::get('/games/index',[GameController::class, 'index']);
Route::get('/sliders/index', [SliderController::class, 'index']);
Route::get('/labels/index', [LabelController::class, 'index']);

// Admin

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

    Route::controller(AdminDashboardController::class)->group(function(){
        Route::get('/dashboard/top/content', 'topContent');
        Route::post('/agent/add/balance/{user}', 'addBalance');
    });

    Route::controller(TransactionController::class)->group(function(){
        Route::get('/transactions', 'adminTransactions');
        Route::get('/transactions/cart/current-month', 'transactionData');
    });

    Route::controller(CurrencyController::class)->group(function(){
        Route::get('/currencies', 'index');
        Route::post('/currencies/store', 'store');
        Route::put('/currencies/update/{currency}', 'update');
    });

    Route::controller(MovingTextController::class)->group(function(){
        Route::get('/texts', 'index');
        Route::post('/texts/store', 'store');
        Route::put('/texts/update/{text}', 'update');
        Route::delete('texts/delete/{text}', 'destroy');
    });

    Route::controller(UserMangeController::class)->group(function(){
        Route::get('find/user/{user}', 'findUser');
        Route::post('update/user/{user}', 'update');
    });

    Route::controller(LabelController::class)->group(function(){
        Route::post('/labels/store', 'store');
        Route::put('/labels/update/{label}', 'update');
    });

    Route::controller(TransactionNumberController::class)->group(function(){
        Route::get('/deposit_numbers/index', 'index');
        Route::post('/deposit_numbers/store', 'store');
        Route::put('/deposit_numbers/update/{number}', 'update');
        Route::delete('/deposit_numbers/delete/{number}', 'delete');
    });

    Route::controller(PromotionController::class)->group(function(){
        Route::post('promotions/store', 'store');
        Route::put('/promotions/update/{promotion}', 'update');
        Route::delete('/promotions/delete/{promotion}', 'destroy');
        Route::get('/promotions/index', 'index');
    });
});
