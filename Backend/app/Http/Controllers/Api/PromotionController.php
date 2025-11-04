<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PromotionResource;
use App\Models\Promotion;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    use FileUploadTrait;

    public function index(){
        $promotions = Promotion::latest()->get();
        return response()->json(['promotions' => PromotionResource::collection($promotions)]);
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'content' => 'required|string',
            'baner_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
            'type' => 'required',
        ]);

        // ফাইল আপলোড করা
        if ($request->hasFile('baner_image')) {
            $desktopPath = $this->storeFile($request->file('baner_image'), 'promotion');
        }
        // ডেটা সেভ করা
        $promotion = Promotion::create([
            'title' => $request->title,
            'status' => $request->status,
            'content' => $request->content,
            'image_url' => $desktopPath ?? null,
            'type' => $request->type,
        ]);

        return response()->json([
            'message' => 'Promotion created successfully',
            'promotion' => new PromotionResource($promotion)
        ]);
    }

    public function update(Request $request, Promotion $promotion){
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'content' => 'required|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'type' => 'required',
        ]);

        if ($request->hasFile('baner_image')) {
            $this->deleteFile($promotion->baner_image);
            $desktopPath = $this->storeFile($request->file('baner_image'), 'promotion');
        }

        $promotion->update([
            'title' => $request->title,
            'status' => $request->status,
            'content' => $request->content,
            'image_url' => $desktopPath ?? $promotion->image_url,
            'type' => $request->type,
        ]);

        return response()->json([
            'message' => 'Promotion updated successfully',
            'promotion' => new PromotionResource($promotion)
        ]);
    }

    public function destroy(Promotion $promotion){
        $promotion->delete();
        $this->deleteFile($promotion->image_url);

        return response()->json([
            'success' => true,
            'message' => 'Promotion deleted successfully'
        ]);
    }
}
