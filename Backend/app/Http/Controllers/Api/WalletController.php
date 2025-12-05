<?php

namespace App\Http\Controllers;

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
        $user = User::where('user_code', $request->userCode)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 0,
                'errorCode' => 2 // user not found
            ]);
        }

        $user->update(['balance' => $user->balance + 200]);
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
