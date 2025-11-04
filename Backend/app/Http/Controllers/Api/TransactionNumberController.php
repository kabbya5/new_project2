<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransactionNumber;
use Illuminate\Http\Request;

class TransactionNumberController extends Controller
{
    public function index(){
        $transaction_numbers = TransactionNumber::all();
        return response()->json(['data' => $transaction_numbers]);
    }

    public function store(Request $request){
        $transaction_number = TransactionNumber::create(
            $request->validate([
                'owner_name' => 'required',
                'provider' => 'required',
                'transaction_type' => 'required',
                'phone_number' => 'required',
                'status' => 'required',
            ])
        );

        return response()->json([
            'data' => $transaction_number,
        ]);
    }

    public function update(Request $request, TransactionNumber $number){
        $number->update(
            $request->validate([
                'owner_name' => 'required',
                'provider' => 'required',
                'transaction_type' => 'required',
                'phone_number' => 'required',
                'status' => 'required',
            ])
        );

        return response()->json([
            'data' => $number,
        ]);
    }

    public function delete(TransactionNumber $number){
        $number->delete();
        return response()->json(['success' => 1]);
    }
}
