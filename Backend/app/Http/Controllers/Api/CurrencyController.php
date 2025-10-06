<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index(){
        $currencies = Currency::all();
        return response()->json(['currencies' => $currencies]);
    }

    public function store(Request $request){
        $request->validate([
            'currency_code' => 'required|unique:currencies,currency_code',
            'english_name' => 'required',
            'brl_rate' => 'required',
        ]);

        $currency = Currency::create($request->all());

        return response()->json(['currency' => $currency]);
    }

    public function update(Request $request, Currency $currency){
        $request->validate([
            'currency_code' => 'required|unique:currencies,currency_code,' .$currency->id,
            'english_name' => 'required',
            'brl_rate' => 'required',
        ]);

        $currency->update($request->all());

        return response()->json(['currency' => $currency]);
    }
}
