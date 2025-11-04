<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LableRsource;
use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index(){
        $labels = Label::orderBy('position')->get();
        return response()->json(['data' =>  LableRsource::collection($labels)]);
    }

    public function store(Request $request){
        $label = Label::create($request->validate([
            'position' => 'required|integer',
            'name' => 'required|string',
            'min_bet' => 'required',
            'max_bet' => 'required',
            'daily_bonus' => 'required',
            'deposit_bonus' => 'nullable|required',
        ]));

        return response()->json(['data' => new LableRsource($label)]);
    }

    public function update(Request $request, Label $label){
        $label->update($request->validate([
            'position' => 'required|integer',
            'name' => 'required|string',
            'min_bet' => 'required',
            'max_bet' => 'required',
            'daily_bonus' => 'nullable|required',
            'deposit_bonus' => 'nullable|required',
        ]));

        return response()->json(['data' => new LableRsource($label)]);
    }

}
