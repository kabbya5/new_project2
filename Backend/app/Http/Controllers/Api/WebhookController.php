<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request){
        try {
            $webhookData = $request->all();
            $after_balance = $webhookData['slot']['user_after_balance'];

            ProcessWebhook::dispatch($webhookData);

            return response()->json(['msg' => 'success', 'balance' => $after_balance], 200);
        } catch (\Exception $e) {
            Log::error("Error handling webhook: " . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }

    }
}
