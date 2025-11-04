<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionNumber;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\FileUploadTrait;
use Carbon\Carbon;

class ManualyTransactionController extends Controller
{
    use FileUploadTrait;

    public function deposite_request(Request $request){
        $request->validate([
            "depositAmount" => "integer|required|min:100",
            "selectedChannel" => 'required',
        ]);

        $provider = $request->selectedChannel;

       $transaction_number = TransactionNumber::where('provider', $provider)
        ->inRandomOrder()->value('phone_number');

        if (!$transaction_number) {
            return response()->json([
                'message' => 'No transaction number found for this provider.',
            ], 404);
        }

        return response()->json([
            'status' => 1,
            'phone_number' => $transaction_number,
            'provider' => $provider,
            'amount' => $request->depositAmount,
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'trxid' => 'required', function ($attribute, $value, $fail) {
                $exists = \App\Models\Transaction::where('order_sn', $value)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->exists();

                if ($exists) {
                    $fail('TrxID already submitted!');
                }
            },
            'provider' => 'required',
            'amount' => 'required',
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            $imageUrl = $this->storeFile($request->file('image'), 'category');
        }

        Transaction::create([
            'user_id' => auth()->id(),
            'image_url' => $imageUrl,
            'type' => 'deposit',
            'status' => 'pending',
            'provider' => strtoupper($request->provider),
            'order_sn' => $request->trxid,
            'amount' => $request->amount,
            'remark' => 'Custom Deposite',
        ]);

        return response()->json([
            'status' => 1,
            'Then deposit store has been store successfully!',
        ]);
    }

    public function withdrawStore(Request $request){
        $request->validate([
            'selectedChannel' => 'required|min:3',
            'amount' => 'required|numeric|min:100',
            'phone_number' => 'required',
            'password' => 'required'
        ]);

        $auth_id = auth()->id();
        $user = User::find($auth_id);
        $amount = $request->input('amount');
        $tradeType = $request->input('selectedChannel');
        $phone_number = $request->input('phone_number');

        if($user->balance < $amount){
            throw new \Exception('Balance is higger than current balance.');
        }

        $order_sn = generate_random_key();

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'affiliate_refer_id' => $user->affiliate_refer_id,
            'type' => 'withdraw',
            'status' =>  'pending',
            'provider' => strtoUpper($tradeType),
            'order_sn' => $order_sn,
            'remark'  => 'Withdraw request',
            'amount' => $amount,
        ]);

        $user->balance = $user->balance - $amount;
        $user->save();

        return response()->json(['status' => 1]);
    }
}
