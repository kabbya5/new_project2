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

    public function index(Request $request){
        $limit = $request->get('limit', 12);
        $games = RecenlyPlay::with('game')->where('user_id', auth()->id())->orderBy('updated_at','desc')->paginate($limit);

        return response()->json(['games' => RecenlyPlayResoure::collection($games)]);
    }

    public function store($id)
    {
        $game = Game::findOrFail($id); // findOrFail ব্যবহার করা ভালো
        $user_id = auth()->id();

        if(!$user_id){
            return null;
        }

        $data = $this->gameService->launchGame($game, $user_id);

        if ($data['status'] === 1 && $data['launch_url']) {
            $game->update(['popularity' => $game->popularity + 1]);

            $recentlyGame = RecenlyPlay::where('user_id', $user_id)->where('game_id', $game->id)->first();

            if($recentlyGame){
                $recentlyGame->update(['updated_at' => now()]);
            }else{
                $recentlyGame = RecenlyPlay::create(
                    [
                        'user_id' => $user_id,
                        'game_id' => $game->id,
                        'updated_at' => now(),
                    ],
                );
            }

            $recentlyGame->load('game.provider', 'game.categories');
        }

        return ['launch_url' => 'https://www.youtube.com/watch?v=HHctFjuNsE0', 'status' => 1];

        return $data;

       	return response()->json($data);
    }

    public function playSports(){
        $user_id = auth()->id();
        $data = $this->gameService->playSports($user_id);
        return $data;
    }
}
