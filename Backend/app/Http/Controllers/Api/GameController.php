<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Services\GameService;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;

class GameController extends Controller
{
    use FileUploadTrait;

    protected GameService $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }


    public function index(){
        $games = Game::with('categories', 'provider')->orderBy('popularity','desc')->take(300)->get();
        return response()->json(['games' => GameResource::collection($games)]);
    }

    public function store(Request $request){
        $request->validate([
            'game_code' => 'required|string',
            'image_url' => 'required|string',
            'english_name' => 'required|string',
            'bangla_name' => 'required|string',
            'hindi_name' => 'required|string',
            'provider_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp',
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            $imageUrl = $this->storeFile($request->file('image'), 'game', 400, 300);
        }

        $game = Game::create([
            'game_code' => $request->game_code,
            'image_url' => $request->image_url,
            'thumbnail' => $imageUrl,
            'english_name' => $request->english_name,
            'bangla_name' => $request->bangla_name,
            'hindi_name' => $request->hindi_name,
            'position' => $request->position,
            'provider_id' => $request->provider_id,
        ]);

        $game->categories()->attach($request->categories);
        $game->load('provider', 'categories');

        return response()->json([
            'message' => 'Category stored successfully!',
            'game' => new GameResource($game),
        ]);
    }

    public function update(Request $request, Game $game){
        $request->validate([
            'game_code' => 'required|string',
            'image_url' => 'required|string',
            'english_name' => 'required|string',
            'bangla_name' => 'required|string',
            'hindi_name' => 'required|string',
            'provider_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp',
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            $this->deleteFile($game->thumbnail);
            $imageUrl = $this->storeFile($request->file('image'), 'game', 400, 300);
        }

        $game->update([
            'game_code' => $request->game_code,
            'image_url' => $request->image_url,
            'thumbnail' => $imageUrl,
            'english_name' => $request->english_name,
            'bangla_name' => $request->bangla_name,
            'hindi_name' => $request->hindi_name,
            'position' => $request->position,
            'provider_id' => $request->provider_id,
        ]);

        $game->categories()->sync(
            collect($request->categories)->map(fn($id) => (int) $id)->toArray()
        );

        $game->load('provider', 'categories');

        return response()->json([
            'message' => 'Category stored successfully!',
            'game' => new GameResource($game),
        ]);
    }
}
