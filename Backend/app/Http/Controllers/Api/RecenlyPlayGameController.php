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

        return ['launch_url' => 'https:\/\/api.playfivers.com\/oneapi?url=https%3A%2F%2Fwbgame.uylci9ta.com%2Fsss%2F%3FssoKey%3Deb2d1c171a85ed2bbdfa899a6a3550daea675e03%26lang%3Den-US%26apiId%3D10106%26be%3Dmoc.at9iclyu.ipabewbw%26domain_gs%3Dat9iclyu%26domain_platform%3Dmoc.at9iclyu.mroftalp-tolsbw%26gameID%3D27%26gs%3Dmoc.at9iclyu.df-tolsbw%26legalLang%3Dtrue%26skin%3D1%26region%3Dna&game=TD_27&t=420a7426-ca22-42e4-9ae9-647391fb8591', 'status' => 1];

        return $data;

       	return response()->json($data);
    }

    public function playSports(){
        $user_id = auth()->id();
        $data = $this->gameService->playSports($user_id);
        return $data;
    }
}
