<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProviderResource;
use App\Models\Category;
use App\Models\Provider;
use App\Services\GameService;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    use FileUploadTrait;

    protected GameService $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function index(){
        $providers = $this->gameService->getProviders();
        $games = $this->gameService->getGames();


        $providers = Provider::with('categories', 'games.provider', 'games.categories')->orderBy('position')->get();

        return response()->json(['providers' => ProviderResource::collection($providers)]);
    }

    public function categoryProviders($slug){
        $category = Category::with('providers')->where('slug', $slug)->first();
        if($category){
            $providers = $category->providers;
            return response()->json(['providers' => ProviderResource::collection($providers)]);
        }

        return [];
    }

    public function store(Request $request){
        $request->validate([
            'english_name' => 'required|string|unique:providers,english_name',
            'bangla_name' => 'required|string',
            'hindi_name' => 'required|string',
            'position' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
            'provider_id' => 'required|string|unique:providers,provider_id',
        ]);

        if ($request->hasFile('image')) {
            $imageUrl = $this->storeFile($request->file('image'), 'provider');
        }

        $provider = Provider::create([
            'logo' => $imageUrl ?? null,
            'english_name' => $request->english_name,
            'bangla_name' => $request->bangla_name,
            'hindi_name' => $request->hindi_name,
            'position' => $request->position,
            'provider_id' => $request->provider_id,
        ]);

        $provider->categories()->attach($request->categories);
        $provider->load('categories', 'games.provider', 'games.categories');

        return response()->json([
            'message' => 'Category stored successfully!',
            'provider' => new ProviderResource($provider),
        ]);
    }

    public function update(Request $request, Provider $provider){

        $request->validate([
            'english_name' => 'required|string|unique:providers,english_name,'.$provider->id,
            'bangla_name' => 'required|string',
            'hindi_name' => 'required|string',
            'position' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
            'provider_id' => 'required|string|unique:providers,provider_id,'.$provider->id,
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            $this->deleteFile($provider->logo);
            $imageUrl = $this->storeFile($request->file('image'), 'provider');
        }

        $provider->update([
            'logo' => $imageUrl ?? $provider->logo,
            'english_name' => $request->english_name,
            'bangla_name' => $request->bangla_name,
            'hindi_name' => $request->hindi_name,
            'position' => $request->position,
            'provider_id' => $request->provider_id,
        ]);


        $provider->categories()->sync(
            collect($request->categories)->map(fn($id) => (int) $id)->toArray()
        );

        $provider->load('categories', 'games.provider', 'games.categories');

        return response()->json([
            'message' => 'Provider updated successfully!',
            'provider' => new ProviderResource($provider),
        ]);
    }
}
