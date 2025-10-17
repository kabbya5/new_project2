<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessWebhook;
use App\Models\Currency;
use App\Models\Game;
use App\Models\GameTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $webhookData = $request->all();
            $after_balance = $webhookData['slot']['user_after_balance'] ?? 0;

            // Dispatch webhook for background processing
            //ProcessWebhook::dispatch($webhookData);

            $user = User::where('user_name', $webhookData['user_code'] ?? null)->first();
            $game = Game::where('game_code', $webhookData['slot']['game_code'] ?? null)->first();

            $bet = $webhookData['slot']['bet'] ?? 0;
            $win = $webhookData['slot']['win'] ?? 0;

            if ($user && $game) {
                $currency = Currency::where('currency_code', $user->currency)->first();

                if ($currency) {
                    // Update user balance and turnover
                    $user->update([
                        'balance' => $after_balance * $currency->brl_rate,
                        'turnover' => $user->turnover > 0
                            ? max(0, $user->turnover - ($win * $currency->brl_rate))
                            : 0,
                    ]);

                    // Create transaction record
                    GameTransaction::create([
                        'user_id' => $user->id,
                        'provider' => $webhookData['slot']['provider_code'] ?? null,
                        'game_type' => $webhookData['game_type'] ?? null,
                        'bet' => $bet,
                        'win' => $win,
                        'before_balance' => $webhookData['slot']['user_before_balance'] ?? 0,
                        'after_balance' => $after_balance,
                        'transaction_id' => $webhookData['slot']['txn_id'] ?? null,
                        'round_id' => $webhookData['slot']['round_id'] ?? null,
                        'game_id' => $game->id,
                        'created_at' => $webhookData['slot']['created_at'] ?? now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return response()->json([
                'msg' => 'success',
                'balance' => $after_balance
            ], 200);

        } catch (\Exception $e) {
            Log::error("Error handling webhook: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
