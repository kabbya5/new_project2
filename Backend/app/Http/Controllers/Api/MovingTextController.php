<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MovingText;
use Illuminate\Http\Request;

class MovingTextController extends Controller
{
    public function index(){
        $texts = MovingText::all();
        return response()->json(['texts' => $texts]);
    }

    public function store(Request $request){
        $request->validate([
            'text' => 'required',
        ]);

        $text = MovingText::create($request->all());

        return response()->json(['text' => $text]);
    }

    public function update(Request $request, MovingText $text){
        $request->validate([
            'text' => 'required',
        ]);

        $text->update($request->all());

        return response()->json(['text' => $text]);
    }

    public function destroy(MovingText $text){
        $text->delete();

        return response()->json(['success' => 1]);
    }
}
