<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecenlyPlayResoure;
use App\Models\Game;
use App\Models\RecenlyPlay;
use Illuminate\Http\Request;
use App\Services\GameService;

class RecenlyPlayGameController extends Controller
{
    protected GameService $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function index(){
        $games = RecenlyPlay::with('game')->orderBy('updated_at','desc')->get();

        return response()->json(['games' => RecenlyPlayResoure::collection($games)]);
    }

    public function store($id)
    {
        $game = Game::findOrFail($id); // findOrFail ব্যবহার করা ভালো
        $game->update(['popularity' => $game->popularity + 1]);
        $user_id = auth()->id();

        if(!$user_id){
            return null;
        }

        $recentlyGame = RecenlyPlay::updateOrCreate(
            [
                'user_id' => $user_id,
                'game_id' => $game->id,
            ],
            [ 'updated_at' => now() ]
        );

        $recentlyGame->save();

        // relation load করা হচ্ছে
        $recentlyGame->load('game.provider', 'game.categories');

        $data = $this->gameService->launchGame($game, $user_id);
        return $data;


       	return response()->json($data);
    }
}
