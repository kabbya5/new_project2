<?php

namespace App\Observers;

use App\Models\Currency;
use App\Models\GameTransaction;
use App\Models\User;
use App\Models\Withdraw;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(GameTransaction $transaction): void
    {
        $user = User::find($transaction->user_id);
        $currency = Currency::where('currency_code',$user->currency)->first();
        $bet = $transaction->after_balance - $transaction->before_balance;
        $bet = $bet * $currency->brl_rate;

        $withdraw = Withdraw::where('user_id', $user->id)->where('status', 1)->first();
        $amount = $transaction->after_balance * $currency->brl_rate;

        if(!$withdraw){
            return;
        }

        if($bet > 0){
            if($bet){
                if($withdraw->min_amount < $amount){
                    $bet *= $withdraw->multipy;
                }

                if($bet > $withdraw->max_amount){
                    $bet = $withdraw->max_amount;
                }

                $amount += $bet;
            }
        }else{
            $bet = $bet * 0.8;
            $amount += abs($bet);
        }

        $user->update([
            'balance' => $amount,
            'turnover' => 0,
        ]);

    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(GameTransaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(GameTransaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(GameTransaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(GameTransaction $transaction): void
    {
        //
    }
}
