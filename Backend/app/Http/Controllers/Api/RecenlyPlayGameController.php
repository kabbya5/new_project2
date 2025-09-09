<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecenlyPlayResoure;
use App\Models\Game;
use App\Models\RecenlyPlay;
use Illuminate\Http\Request;

class RecenlyPlayGameController extends Controller
{
    public function index(){
        $games = RecenlyPlay::with('game')->orderBy('updated_at','desc')->get();

        return response()->json(['games' => RecenlyPlayResoure::collection($games)]);
    }

    public function store($id)
    {
        $game = Game::findOrFail($id); // findOrFail ব্যবহার করা ভালো
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

        return response()->json([
            'message' => 'Recently played game stored successfully!',
            'game' => new RecenlyPlayResoure($recentlyGame), // এখানে ভুল ছিল
        ]);
    }
}
