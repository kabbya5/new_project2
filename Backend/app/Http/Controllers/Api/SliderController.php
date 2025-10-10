<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResoource;
use App\Models\Slider;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    use FileUploadTrait;

    public function index(){
        $sliders = Slider::latest()->get();

        return response()->json(['sliders' => SliderResoource::collection($sliders)]);
    }

    public function store(Request $request){
        $request->validate([
            'slider_name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'slider_content' => 'required|string',
            'desktop_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
            'mobile_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        // ফাইল আপলোড করা
        if ($request->hasFile('desktop_image')) {
            $desktopPath = $this->storeFile($request->file('desktop_image'), 'slider');
        }

        if ($request->hasFile('mobile_image')) {
            $mobilePath = $this->storeFile($request->file('mobile_image'), 'slider');
        }

        // ডেটা সেভ করা
        $slider = Slider::create([
            'slider_name' => $request->slider_name,
            'status' => $request->status,
            'slider_content' => $request->slider_content,
            'desktop_image' => $desktopPath ?? null,
            'mobile_image' => $mobilePath ?? null,
        ]);

        return response()->json([
            'message' => 'Slider created successfully',
            'slider' => new SliderResoource($slider)
        ]);
    }

    public function update(Request $request, Slider $slider){
        $request->validate([
            'slider_name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'slider_content' => 'required|string',
            'desktop_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'mobile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        if ($request->hasFile('desktop_image')) {
            $this->deleteFile($slider->desktop_image);
            $desktopPath = $this->storeFile($request->file('desktop_image'), 'slider');
        }

        if ($request->hasFile('mobile_image')) {
            $this->deleteFile($slider->mobile_image);
            $mobilePath = $this->storeFile($request->file('mobile_image'), 'slider');
        }

        $slider->update([
            'slider_name' => $request->slider_name,
            'status' => $request->status,
            'slider_content' => $request->slider_content,
            'desktop_image' => $desktopPath ?? $slider->desktop_image,
            'mobile_image' => $mobilePath ?? $slider->mobile_image,
        ]);

        return response()->json([
            'message' => 'Slider created successfully',
            'slider' => new SliderResoource($slider)
        ]);
    }

    public function destroy(Slider $slider){
        $slider->delete();
        $this->deleteFile($slider->desktop_image);
        $this->deleteFile($slider->mobile_image);

        return response()->json([
            'success' => true,
            'message' => 'Slider deleted successfully'
        ]);
    }
}
