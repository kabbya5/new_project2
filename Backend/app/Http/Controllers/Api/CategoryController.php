<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\FileUploadTrait;

class CategoryController extends Controller
{
    use FileUploadTrait;

    public function index(){

        $categories = Category::with('providers')->orderBy('position')->get();

        return response()->json(['categories' => CategoryResource::collection($categories)]);
    }

    public function store(Request $request){
        $request->validate([
            'english_name' => 'required',
            'bangla_name' => 'required',
            'hindi_name' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            $imageUrl = $this->storeFile($request->file('image'), 'category');
        }

        $category = Category::create([
            'image_url' => $imageUrl,
            'english_name' => $request->english_name,
            'bangla_name' => $request->bangla_name,
            'hindi_name' => $request->hindi_name,
            'position' => $request->position,
        ]);

        return response()->json([
            'message' => 'Category stored successfully!',
            'category' => new CategoryResource($category),
        ]);
    }

    public function update(Request $request, Category $category){

        $request->validate([
            'english_name' => 'required|string',
            'bangla_name' => 'required|string',
            'hindi_name' => 'required|string',
            'position' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            $this->deleteFile($category->image_url);
            $imageUrl = $this->storeFile($request->file('image'), 'category');
        }


        $category->update([
            'image_url' => $imageUrl ?? $category->image_url,
            'english_name' => $request->english_name,
            'bangla_name' => $request->bangla_name,
            'hindi_name' => $request->hindi_name,
            'position' => $request->position,
        ]);

        return response()->json([
            'message' => 'Category updated successfully!',
            'category' => new CategoryResource($category),
        ]);
    }
}
