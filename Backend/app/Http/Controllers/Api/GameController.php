<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Services\DSTGameService;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;

class GameController extends Controller
{
    use FileUploadTrait;

    protected DSTGameService $dstGameService;

    public function __construct(DSTGameService $dstGameService)
    {
        $this->dstGameService = $dstGameService;
    }

    public function playGame($game){
        //
    }

    public function index(){
        $games = Game::with('categories', 'provider')->orderBy('popularity','desc')->get();
        return response()->json(['games' => GameResource::collection($games)]);
    }

    public function store(Request $request){
        $request->validate([
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
            'thumbnail' => $imageUrl,
            'english_name' => $request->english_name,
            'bangla_name' => $request->bangla_name,
            'hindi_name' => $request->hindi_name,
            'position' => $request->position,
            'provider_id' => $request->provider_id,
        ]);

        $game->categories()->attach($request->categories);

        return response()->json([
            'message' => 'Category stored successfully!',
            'game' => new GameResource($game),
        ]);
    }
}
