<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameTransaction;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Models\User;

class WalletController extends Controller
{
    public function balance(Request $request)
    {
        // 1. Check Basic Auth
        if (!$this->checkAuth($request)) {
            return response()->json([
                'success' => false,
                'message' => 0,
                'errorCode' => 401
            ], 401);
        }

        // 2. Validate input
        $request->validate([
            'userCode' => 'required|string'
        ]);

        // 3. Find user by userCode
        $user = User::where('user_name', $request->userCode)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 0,
                'errorCode' => 2,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $user->balance,
            'errorCode' => 0
        ]);
    }

    public function transaction(Request $request){
        \Log::info("BATCH REQUEST RECEIVED", [
            "payload" => $request->all()
        ]);

        $validated = $request->validate([
            'userCode'          => 'required|string',
            'vendorCode'        => 'required|string',
            'gameCode'          => 'required|string',
            'historyId'         => 'required|integer',
            'roundId'           => 'required|string',
            'gameType'          => 'required|integer',
            'transactionCode'   => 'required|string',
            'isFinished'        => 'required|boolean',
            'isCanceled'        => 'required|boolean',
            'amount'            => 'required|numeric',
            'detail'            => 'nullable|string',
            'createdAt'         => 'required|date',
        ]);


        $user = User::where('user_name', $request->userCode)->first();

        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'User Not Found',
                'errorCode' => 404
            ]);
        }

        $finalBalance = $user->balance;

        $exist = GameTransaction::where('transaction_id', $validated['transactionCode'])->first();

        if($exist){
            return response()->json([
                'success' => false,
                'message' => 'Duplicate transaction',
                'errorCode' => 'DUPLICATE_TRANSACTION',
            ]);
        }

        if($validated['isFinished']){
            $roundExist = GameTransaction::where('round_id', $validated['roundId'])->first();
            if($roundExist){
                return response()->json([
                    'success' => false,
                    'message' => "Invalid Transaction",
                    'code' => "INVALID_TRANSACTION",
                ]);
            }
        }

        $before_balance = $finalBalance;

        $finalBalance = $finalBalance  + $validated['amount'];

        $game = Game::where('game_code', $validated['gameCode'])->first();

        GameTransaction::create([
            'user_id' => $user->id,
            'game_id' => $game->id,
            'provider' => $game->provider->english_name,
            'game_type' => $validated['gameType'] == 1 ? 'slot' : 'unknown',
            'bet' => $validated['amount'],
            'win' => $validated['amount'] > 0 ? $validated['amount'] : 0,
            'before_balance' => $before_balance,
            'after_balance' => $finalBalance,
            'transction_id' => $validated['transactionCode'],
            'round_id' => $validated['roundId']
        ]);

        $user->balance = $finalBalance;
        $user->save();

        return response()->json([
            'success' => True,
            'messsage' => $user->balance,
            'errorCode' => 0
        ]);
    }

    public function batchTransactions(Request $request){

        if (!$this->checkAuth($request)) {
            return response()->json([
                'success' => false,
                'message' => 0,
                'errorCode' => 401
            ], 401);
        }

        try{
            $request->validate([
                'userCode' => 'required|string',
                'transactions' => 'required|array|min:1',
            ]);

            $user = User::where('user_name', $request->userCode)->first();

            if(!$user){
                return response()->json([
                    'success' => false,
                    'message' => 'User Not Found',
                    'errorCode' => 404
                ]);
            }

            $finalBalance = $user->balance;

            foreach($request->transactions as $tx){
                $exist = GameTransaction::where('transaction_id', $tx['transactionCode'])->first();

                if($exist){
                    return response()->json([
                        'success' => false,
                        'message' => 'Duplicate transaction',
                        'errorCode' => 'DUPLICATE_TRANSACTION',
                    ]);
                }

                if($tx['isFinished']){
                    $roundExist = GameTransaction::where('round_id', $tx['roundId'])->first();
                    if($roundExist){
                        return response()->json([
                            'success' => false,
                            'message' => "Invalid Transaction",
                            'code' => "INVALID_TRANSACTION",
                        ]);
                    }
                }

                $before_balance = $finalBalance;

                $finalBalance = $finalBalance  + $tx['amount'];

                $game = Game::where('game_code', $tx['gameCode'])->first();

                GameTransaction::create([
                    'user_id' => $user->id,
                    'game_id' => $game->id,
                    'provider' => $game->provider->english_name,
                    'game_type' => $tx['gameType'] == 1 ? 'slot' : 'unknown',
                    'bet' => $tx['amount'],
                    'win' => $tx['amount'] > 0 ? $tx['amount'] : 0,
                    'before_balance' => $before_balance,
                    'after_balance' => $finalBalance,
                    'transction_id' => $tx['transactionCode'],
                    'round_id' => $tx['roundId']
                ]);

                $user->balance = $finalBalance;
                $user->save();
            }

            return response()->json([
                'success' => True,
                'messsage' => $user->balance,
                'errorCode' => 0
            ]);

        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errorCode' => 500,
            ], 500);
        }
    }

    // Check Basic Auth
    private function checkAuth(Request $request)
    {
        $header = $request->header('Authorization');

        if (!$header || !str_starts_with($header, 'Basic ')) {
            return false;
        }

        $encoded = substr($header, 6);
        $decoded = base64_decode($encoded);
        [$clientId, $clientSecret] = explode(':', $decoded);

        return $clientId === config('services.oroplay.client_id')
            && $clientSecret === config('services.oroplay.client_secret');
    }
}
