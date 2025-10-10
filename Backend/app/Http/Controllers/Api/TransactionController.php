<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\User;
use App\Services\LPGService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class TransactionController extends Controller
{
    protected LPGService $lgpservice;

    public function __construct(LPGService $lgpservice)
    {
        $this->lgpservice = $lgpservice;
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
            'affiliate_refer_id' =>  $other_refer_id,
            'other_refer_id' =>  $affiliate_refer_id,
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

        $user->balance = $user->balance - $amount;
        $user->save();

        $order_sn = generate_random_key();

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
        try {
            $response = $this->lgpservice->payOutOrder($amount, $tradeType, $phone_number, $user,  $order_sn);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
            $user->update(['amount' => $user->balance + $amount]);
        }

        return 'ok';
    }

    public function adminTransactions(Request $request){
        $limit = $request->limit;
        $type = $request->type;
        $month = $request->month;
        $years = $request->year;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $user_id = $request->user_id;
        $status = $request->status;

        $from_date = $from_date
            ? Carbon::parse($from_date)->format('Y-m-d')
            : Carbon::now()->startOfMonth()->format('Y-m-d');

        $to_date = $to_date
            ? Carbon::parse($to_date)->format('Y-m-d')
            : Carbon::now()->endOfMonth()->format('Y-m-d');

        $data = Transaction::query()
            ->when($user_id, function($query) use ($user_id){
                $query->where('user_id', $user_id);
            })
            ->when($status, function($query) use ($status){
                $query->where('status', $status);
            })
            ->when($type, function($query) use ($type){
                if($type == 'all_bonus'){
                    $query->whereIn('type',['bonus','refer_bonus']);
                }elseif($type == 'all_transaction'){
                    $query->whereIn('type',['deposit','withdraw']);
                }else{
                    $query->where('type', $type);
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
        $limit = $request->limit;
        $page = $request->page;
        $type = $request->type;
        $month = $request->month;
        $years = $request->year;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $user_id = $request->user_id;
        $status = $request->status;

        return $from_date;


        $data = Transaction::query()
            ->when($user_id, function($query) use ($user_id){
                $query->where('user_id', $user_id);
            })
            ->when($status, function($query) use ($status){
                $query->where('status', $status);
            })
            ->when($type, function($query) use ($type){
                $query->where('type', $type);
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
}
