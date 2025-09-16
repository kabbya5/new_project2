<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GameService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public GameService $gameService;

    public function __construct(GameService $gameService) {
        $this->gameService = $gameService;
    }

    public function balance(){
        $user_id = 3;

        if(!$user_id){
            return [];
        }

        $data = $this->gameService->balance($user_id);

        return $data;
    }

    public function transaction(){

        $user_id = auth()->user_id;

        if(!$user_id){
            return [];
        }

        $data = $this->gameService->transaction($user_id);

        return $data;
    }
}
