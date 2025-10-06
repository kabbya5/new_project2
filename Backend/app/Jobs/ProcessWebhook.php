<?php

namespace App\Jobs;

use App\Models\Currency;
use App\Models\Game;
use App\Models\GameTransaction;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessWebhook implements ShouldQueue
{
    use Queueable;

    public $webhookData;

    /**
     * Create a new job instance.
     */
    public function __construct($webhookData)
    {
        $this->webhookData = $webhookData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $data = $this->webhookData;

            // Extract necessary data from the webhook
            $user = User::where('user_name', $data['user_code'])->first();
            $game = Game::where('game_code', $data['slot']['game_code'])->first();

            $amount = $data['slot']['user_after_balance'];

            if ($user && $game) {
                $currency = Currency::where('currency_code',$user->currency)->first();
                $win = $data['slot']['bet'];
                $user->update([
                    'balance' => $amount * $currency->brl_rate,
                    'turnover' => $user->turnover > 0 ? $user->turnover - ($win * $currency->brl_rate) : 0,
                ]);

                $transaction = GameTransaction::create([
                    'user_id' => $user->id,
                    'provider' => $data['slot']['provider_code'],
                    'game_type' => $data['game_type'],
                    'bet' => $data['slot']['bet'],
                    'win' => $data['slot']['win'],
                    'before_balance' => $data['slot']['user_before_balance'],
                    'after_balance' => $data['slot']['user_after_balance'],
                    'transaction_id' => $data['slot']['txn_id'],
                    'round_id' => $data['slot']['round_id'],
                    'game_id' => $game->id,
                    'created_at' => $data['slot']['created_at'],
                    'updated_at' => now(),
                ]);
            } else {
                Log::error("User not found for user_code: {$data['user_code']}");
            }

        } catch (\Exception $e) {
            Log::error("Error processing webhook: " . $e->getMessage());
        }
    }
}
