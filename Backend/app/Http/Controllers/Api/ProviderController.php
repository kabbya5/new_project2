<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    use FileUploadTrait;

    public function index(){

        $providers = Provider::with('categories', 'games.provider', 'games.categories')->orderBy('position')->get();

        return response()->json(['providers' => ProviderResource::collection($providers)]);
    }

    public function store(Request $request){
        $request->validate([
            'english_name' => 'required|string',
            'bangla_name' => 'required|string',
            'hindi_name' => 'required|string',
            'position' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);


        $fileUrl = null;

        if ($request->hasFile('image')) {
            $fileUrl = $this->storeFile($request->file('image'), 'category',100,100);
        }

        $provider = Provider::create([
            'logo' => $fileUrl,
            'english_name' => $request->english_name,
            'bangla_name' => $request->bangla_name,
            'hindi_name' => $request->hindi_name,
            'position' => $request->position,
        ]);

        $provider->categories()->attach($request->categories);

        return response()->json([
            'message' => 'Category stored successfully!',
            'provider' => new ProviderResource($provider),
        ]);
    }
}
