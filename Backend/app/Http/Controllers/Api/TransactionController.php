<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\User;
use App\Services\LPGService;
use App\Services\OroPlayService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class TransactionController extends Controller
{
    protected LPGService $lgpservice;
    protected OroPlayService $oroplayService;

    public function __construct(LPGService $lgpservice, OroPlayService $oroplayService)
    {
        $this->lgpservice = $lgpservice;
        $this->oroplayService = $oroplayService;
    }

    public function depostie(Request $request){
        $user = auth()->user();
        $amount = $request->input('depositAmount');
        $tradeType = $request->input('selectedChannel');

        $order_sn = generate_random_key();

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'status' => 'pending',
            'provider' => strtoupper($tradeType),
            'order_sn' => $order_sn,
            'amount' => $amount,
            'remark' => 'Pending Request',
        ]);

        try {
            $response = $this->lgpservice->createOrder($amount, $tradeType, $user, $order_sn);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function depositeNotification(Request $request){
        $user = User::where('user_name',$request->remark)->first();
        $affiliate_refer_id = $user->affiliate_refer_id ?? null;
        $other_refer_id = $user->other_refer_id ?? null;
        $amount = $request->money / 100;

        $order_sn = $request->order_sn;

        $transaction = Transaction::where('order_sn', $order_sn)->where('status','pending')->first();

        if (!$transaction) {
            throw new \Exception('No transaction found for this order_sn.');
        }

        $transaction->update([
            'user_id' => $user->id,
            'affiliate_refer_id' => $affiliate_refer_id,
            'other_refer_id' =>  $other_refer_id,
            'type' => 'deposit',
            'status' =>  $request->status == '1' ? 'success' : 'failed',
            'order_sn' => $request->order_sn,
            'remark' => $request->msg,
            'amount' => $amount,
        ]);

        if($request->status == '1'){
            $user->update([
                'balance' => $user->balance  + $amount,
                'turnover' => $user->turnover + $amount,
            ]);

            if ($affiliate_refer_id) {
                $affiliate = User::find($affiliate_refer_id);
                if ($affiliate) {
                    $commission = $amount * 0.30;
                    $affiliate->increment('balance', $commission);

                    Transaction::create([
                        'user_id' => $affiliate->id,
                        'type'    => 'agent_bouns',
                        'status'  => 'success',
                        'provider' => $transaction->provider,
                        'order_sn' => $request->order_sn,
                        'remark'  => "Affiliate commission from {$user->user_name}",
                        'amount'  => $commission,
                    ]);
                }
            }

            if ($other_refer_id) {
                $otherRefer = User::find($other_refer_id);
                if ($otherRefer) {
                    $commission = $amount * 0.03;
                    $otherRefer->increment('balance', $commission);

                    Transaction::create([
                        'user_id' => $otherRefer->id,
                        'type'    => 'refer_bonus',
                        'status'  => 'success',
                        'provider' => $transaction->provider,
                        'order_sn' => $request->order_sn,
                        'remark'  => "Refer commission from {$user->user_name}",
                        'amount'  => $commission,
                    ]);
                }
            }
        }

        return 'ok';
    }

    public function withdrow(Request $request){

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

        if($user->currency == 'bdt'){
            $phone_number =  preg_replace('/^\+88/', '', $phone_number);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The given password was invalid.',
                'errors' => [
                    'password' => ['Invalid credentials.']
                ]
            ], 422);
        }

        $order_sn = generate_random_key();

        try {
            $response = $this->lgpservice->payOutOrder($amount, $tradeType, $phone_number, $user,  $order_sn);
            if($response['status'] !== 0){
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'affiliate_refer_id' => $user->affiliate_refer_id,
                    'type' => 'withdraw',
                    'status' =>  'pending',
                    'provider' => strtoUpper($tradeType),
                    'order_sn' => $order_sn,
                    'remark'  => 'Pending Withdraw',
                    'amount' => $amount,
                ]);

                $user->balance = $user->balance - $amount;
                $user->save();

                return response()->json($response);
            }

            return response()->json(['error' => 'Withdraw request failed, try again'], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function withdrawNotification(Request $request){
        Log::debug('Withdraw request received', $request->all());

        $amount = $request->money / 100;

        $order_sn = $request->order_sn;

        $transaction = Transaction::where('order_sn', $order_sn)->where('status', 'pending')->first();

        if (!$transaction) {
            throw new \Exception('No transaction found in session.');
        }

        $user = User::find($transaction->user_id);
        $affiliate_refer_id = $user->affiliate_refer_id;

        $provider = $transaction->provider;

        $transaction->update([
            'user_id' => $user->id,
            'affiliate_refer_id' =>  $affiliate_refer_id,
            'type' => 'withdraw',
            'status' =>  $request->status == '1' ? 'success' : 'failed',
            'provider' => strtoUpper($provider),
            'order_sn' => $request->order_sn,
            'remark'  => $request->msg,
            'amount' => $amount,
        ]);


        if($request->status == 1){
            if ($affiliate_refer_id) {
                $affiliate = User::find($affiliate_refer_id);
                if ($affiliate) {
                    $commission = $amount * 0.30;
                    $affiliate->update(['balance' => $affiliate->balance - $commission]);

                    Transaction::create([
                        'user_id' => $affiliate->id,
                        'type'    => 'reduce_commission',
                        'status'  => 'success',
                        'provider' => strtoUpper($provider),
                        'order_sn' => $request->order_sn,
                        'remark'  => "Affiliate commission from {$user->user_name}",
                        'amount'  => $commission,
                    ]);
                }
            }
        }else{
            $user->update(['balance' => $user->balance + $amount]);
        }

        return 'ok';
    }

    public function adminTransactions(Request $request){
        $limit = $request->limit;
        $type = $request->type;
        $month = $request->month;
        $years = null;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $user_id = null;
        $user_type = null;
        $status = $request->status;
        $search = $request->searchQuery == 'all' ? null : $request->searchQuery;
      
      	

        if(auth()->user()->role != 'admin'){
            $user_id =  auth()->id();
            $user_type = auth()->user()->role;
        }
      

        $from_date = $from_date
            ? Carbon::parse($from_date)->format('Y-m-d')
            : Carbon::now()->startOfMonth()->format('Y-m-d');

        $to_date = $to_date
            ? Carbon::parse($to_date)->addDay()->format('Y-m-d')
            : Carbon::now()->endOfMonth()->addDay()->format('Y-m-d');

        $data = Transaction::query()
            ->latest()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('order_sn', 'like', '%' . $search . '%')
                    ->orWhere('amount', $search);
                });
            })
            ->when($user_id, function ($query) use ($user_id, $user_type) {
                $query->where(function ($q) use ($user_id, $user_type) {
                    if ($user_type === 'agent') {
                        $q->where('user_id', $user_id)
                        ->orWhere('agent_id', $user_id);
                    } elseif ($user_type === 'affiliate') {
                        $q->where('user_id', $user_id)
                        ->orWhere('affiliate_refer_id', $user_id);
                    } else {
                        $q->where('user_id', $user_id);
                    }
                });
            })
            ->when($type, function($query) use ($type){

                if($type == 'all_bonus'){
                    $query->whereIn('type',['bonus','refer_bonus']);
                }elseif($type == 'all_transaction'){
                    $query->whereIn('type',['deposit','withdraw','agent_bouns', 'reduce_commission','refer_bonus','bonus', ]);
                }elseif($type == 'bonus'){
                    $query->whereIn('type',['agent_bouns','refer_bonus','bonus','reduce_commission']);
                }else{
                    $query->where('type', $type);
                }
            })->when($status, function($query) use ($status){
                if($status == 'all'){
                    $query->whereIn('status',['pending','success']);
                }else{
                    $query->where('status', $status);
                }
            })
            ->when($month, function($query) use ($month){
                $query->whereMonth('created_at', $month);
            })
            ->when($years, function($query) use ($years){
                $query->whereYear('created_at', $years);
            })
            ->when($from_date && $to_date, function($query) use ($from_date, $to_date){
                $query->whereBetween('created_at', [$from_date, $to_date]);
            })->when($from_date && !$to_date, function($query) use ($from_date){
                $query->whereDate('created_at', '>=', $from_date);
            })->when(!$from_date && $to_date, function($query) use ($to_date){
                $query->whereDate('created_at', '<=', $to_date);
            })
            ->paginate($limit);

	
        return response()->json([
            'transactions' => TransactionResource::collection($data),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page'    => $data->lastPage(),
                'per_page'     => $data->perPage(),
                'total'        => $data->total(),
            ]
        ]);
    }

    public function transactionData(Request $request)
    {

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $data = DB::table('transactions')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(CASE WHEN type = "deposit" THEN amount ELSE 0 END) as deposit'),
                DB::raw('SUM(CASE WHEN type = "withdraw" THEN amount ELSE 0 END) as withdraw')
            )
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return response()->json(['data' => $data]);
    }

    public function takeBonus($bonus){
        $auth_id = auth()->id();
        $user = User::find($auth_id);

        $amount = $bonus;
        $order_sn = generate_random_key();

        Transaction::create([
            'user_id' => $user->id,
            'affiliate_refer_id' => null,
            'other_refer_id' =>  null,
            'type' => 'bonus',
            'status' =>  'success',
            'order_sn' => $order_sn,
            'remark' => 'User Daily Bonus',
            'amount' => $amount,
        ]);

        $user->update(['balance' => $user->balance + $bonus]);

        return response()->json(['status' => 1]);

    }

    public function store(Request $request){
        $request->validate([
            'user_name' => 'required|exists:users,user_name',
            'type' => 'required',
            'transaction_code' => 'required',
            'amount' => 'required|numeric',
        ]);

        $admin = User::find(auth()->id());

        $user = User::where('user_name', $request->user_name)->first();

        if (!$user) {
            return response()->json([
                'status' => 0,
                'message' => 'User not found.',
            ], 404);
        }

        if ($admin->role == 'agent' && $admin->balance < $request->amount) {
            return response()->json([
                'status' => 'error',
                'errors' => [
                    'amount' => ['Agent amount is less than the transaction amount.']
                ]
            ], 422);
        }

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'order_sn' => $request->transaction_code,
            'amount' => $request->amount,
            'provider' => 'Custom',
            'remark' => 'Custom Transaction',
            'status' => 'pending',
            'agent_id' => $admin->role == 'agent' ? $admin->id : null,
        ]);

        if($admin->role == 'agent'){
            $admin->update(['balance' => $admin->balance - $request->amount]);
            $transaction->update(['remark' => 'Transaction by agent']);
        }

        return response()->json([
            'transaction' => new TransactionResource($transaction),
        ]);
    }

    public function update(Request $request, Transaction $transaction){
        $request->validate([
            'user_name' => 'required|exists:users,user_name',
            'type' => 'required',
            'transaction_code' => 'required',
            'amount' => 'required|numeric',
        ]);

        $user = User::where('user_name', $request->user_name)->first();

        if (!$user) {
            return response()->json([
                'status' => 0,
                'message' => 'User not found.',
            ], 404);
        }

        $transaction->update([
            'user_id' => $user->id,
            'type' => $request->type,
            'order_sn' => $request->transaction_code,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        return response()->json([
            'transaction' => new TransactionResource($transaction),
        ]);
    }

    public function delete(Transaction $transaction){
        $user_role = auth()->user()->role;

        if($user_role == 'user'){
            return;
        }

        if($transaction->agent_id > 0){
            $agent = User::find($transaction->agent_id);
            if($agent){
                $agent->update(['balance' => $agent->balance + $transaction->amount]);
            }
        }

        $transaction->delete();

        return response()->json(['success' => 1]);
    }

    public function approval(Transaction $transaction){

        if($transaction->type == 'deposit'){
            $this->customDeposit($transaction);
        }else{
            $check = $this->customWithdraw($transaction);
        }

        $transaction->update(['status' => 'success']);

        return response()->json([
            'transaction' => new TransactionResource($transaction),
        ]);
    }

    public function customDeposit($transaction){

        $user = User::find($transaction->user_id);
        $affiliate_refer_id = $user->affiliate_refer_id ?? null;
        $other_refer_id = $user->other_refer_id ?? null;
        $amount = $transaction->amount;

        $custom_commission = $amount * 0.05;

        $user->update([
            'balance' => $user->balance  + $amount + $custom_commission,
            'turnover' => $user->turnover + $amount,
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'type'    => 'bonus',
            'status'  => 'success',
            'provider' => $transaction->provider,
            'order_sn' => $transaction->order_sn,
            'remark'  => "Transaction from deposit {$amount}",
            'amount'  => $custom_commission,
        ]);

        $amount = $amount + $custom_commission;

        $this->oroplayService->createUser($user->user_name);
        $this->oroplayService->deposite($user->user_name,$amount,$transaction->order_sn);

        if ($affiliate_refer_id) {
            $affiliate = User::find($affiliate_refer_id);
            if ($affiliate) {
                $commission = $amount * 0.30;
                $affiliate->increment('balance', $commission);

                Transaction::create([
                    'user_id' => $affiliate->id,
                    'type'    => 'agent_bouns',
                    'status'  => 'success',
                    'provider' => $transaction->provider,
                    'order_sn' => $transaction->order_sn,
                    'remark'  => "Affiliate commission from {$user->user_name}",
                    'amount'  => $commission,
                ]);
            }
            $this->oroplayService->createUser($affiliate->user_name);
            $this->oroplayService->deposite($affiliate->user_name,$commission,$transaction->order_sn . 'QS');
        }

        if ($other_refer_id) {
            $otherRefer = User::find($other_refer_id);
            if ($otherRefer) {
                $commission = $amount * 0.03;
                $otherRefer->increment('balance', $commission);

                Transaction::create([
                    'user_id' => $otherRefer->id,
                    'type'    => 'refer_bonus',
                    'status'  => 'success',
                    'provider' => $transaction->provider,
                    'order_sn' => $transaction->order_sn,
                    'remark'  => "Refer commission from {$user->user_name}",
                    'amount'  => $commission,
                ]);
            }

            $this->oroplayService->createUser($otherRefer->user_name);
            $this->oroplayService->deposite($otherRefer->user_name,$commission,$transaction->order_sn . 'AQS');
        }

        if($transaction->agent_id){
            $agent = User::find($transaction->agent_id);
            if($agent){
                $bonus = $amount * 0.05;
                Transaction::create([
                    'user_id' => $agent->id,
                    'type'    => 'bonus',
                    'status'  => 'success',
                    'provider' => $transaction->provider,
                    'order_sn' => $transaction->order_sn,
                    'remark'  => "Transaction bonus from {$user->user_name}",
                    'amount'  => $bonus,
                ]);

                $agent->update(['balance' => $agent->balance + $bonus]);

                $this->oroplayService->createUser($agent->user_name);
                $this->oroplayService->deposite($agent->user_name,$commission,$transaction->order_sn . 'AQS');
            }
        }
    }

    private function customWithdraw($transaction){
        $amount = $transaction->amount;
        $user = User::find($transaction->user_id);

        if($user->balance < $transaction->amount){
            return false;
        }

        if($user->turnover > 0){
            return false;
        }

        $user->update(['balance' => $user->balance - $transaction->amount]);

        $this->oroplayService->createUser($user->user_name);
        $this->oroplayService->withdraw($user->user_name,$amount,$transaction->order_sn);

        $affiliate_refer_id = $user->affiliate_refer_id;

        $provider = $transaction->provider;

        if ($affiliate_refer_id) {
            $affiliate = User::find($affiliate_refer_id);
            if ($affiliate) {
                $commission = $amount * 0.30;
                $affiliate->update(['balance' => $affiliate->balance - $commission]);

                Transaction::create([
                    'user_id' => $affiliate->id,
                    'type'    => 'reduce_commission',
                    'status'  => 'success',
                    'provider' => strtoUpper($provider),
                    'order_sn' => $transaction->order_sn,
                    'remark'  => "Affiliate reduce commission from {$user->user_name} withdraw",
                    'amount'  => $commission,
                ]);

                $this->oroplayService->createUser($affiliate->user_name);
                $this->oroplayService->withdraw($affiliate->user_name,$amount,$transaction->order_sn);

            }
        }

        if ($transaction->agent_id) {
            $agent = User::find($transaction->agent_id);
            if ($agent) {
                $commission = $amount * 0.03;
                $agent->update(['balance' => $agent->balance + $commission]);

                Transaction::create([
                    'user_id' => $agent->id,
                    'type'    => 'bonus',
                    'status'  => 'success',
                    'provider' => strtoUpper($provider),
                    'order_sn' => $transaction->order_sn,
                    'remark'  => "Agent withdraw bonus from {$user->user_name} withdraw",
                    'amount'  => $commission,
                ]);

                $this->oroplayService->createUser($agent->user_name);
                $this->oroplayService->withdrawall($agent->user_name,$amount,$transaction->order_sn);

            }
        }

        return true;
    }
}
