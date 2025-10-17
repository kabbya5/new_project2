<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BettingResource;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\GameTransaction;
use App\Services\GameService;
use App\Traits\FileUploadTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GameController extends Controller
{
    use FileUploadTrait;

    protected GameService $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }


    public function index(Request $request){

        $limit = $request->limit;

        $search = $request->search;
        $provider_id = (int) $request->provider_id;

        $games = Game::with('provider')->orderBy('popularity','desc')
            ->when($search, function($q) use($search){
                $q->where(function($q2) use($search) {
                    $q2->where('english_name', 'like', '%'.$search.'%')
                    ->orWhere('game_code', 'like', '%'.$search.'%');
                });
            })->when($provider_id, function($q) use($provider_id){
                $q->where('provider_id', $provider_id);
            })
            ->paginate($limit);
        return response()->json([
            'games' => GameResource::collection($games),
            'pagination' => [
                'current_page' => $games->currentPage(),
                'last_page'    => $games->lastPage(),
                'per_page'     => $games->perPage(),
                'total'        => $games->total(),
            ]
        ]);
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

    public function gameRecords(Request $request){
        $limit = $request->limit;
        $month = $request->month;
        $year = $request->year;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $user_id = null;
        if(auth()->user()->type == 'user'){
            $user_id =  auth()->id();
        }

        $from_date = $from_date
            ? Carbon::parse($from_date)->format('Y-m-d')
            : Carbon::now()->startOfMonth()->format('Y-m-d');

        $to_date = $to_date
            ? Carbon::parse($to_date)->format('Y-m-d')
            : Carbon::now()->endOfMonth()->format('Y-m-d');

        $data = GameTransaction::query()
            ->when($user_id, function($query) use ($user_id){
                $query->where('user_id', $user_id);
            })
            ->when($month, function($query) use ($month){
                $query->whereMonth('created_at', $month);
            })
            ->when($year, function($query) use ($year){
                $query->whereYear('created_at', $year);
            })
            ->when($from_date && $to_date, function($query) use ($from_date, $to_date){
                $query->whereBetween('created_at', [$from_date, $to_date]);
            })->when($from_date && !$to_date, function($query) use ($from_date){
                $query->whereDate('created_at', '>=', $from_date);
            })->when(!$from_date && $to_date, function($query) use ($to_date){
                $query->whereDate('created_at', '<=', $to_date);
            })
            ->paginate($limit);


        return response()->json([
            'records' => BettingResource::collection($data),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page'    => $data->lastPage(),
                'per_page'     => $data->perPage(),
                'total'        => $data->total(),
            ]
        ]);
    }
}
