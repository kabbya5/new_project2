<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:2'],
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user = User::find(1);

        $token = $user->createToken('authentication')->plainTextToken;

        return response()->json([
            'message' => 'User registred successfully',
            'user' => $user,
            'token'  => $token,
        ]);

    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6', // Ensure password is a string with at least 6 characters
        ]);

        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();

            if ($user) {
                $token = $user->createToken('authentication')->plainTextToken;

                return response()->json([
                    'user' => $user,
                    'token' => $token,
                ]);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'No authenticated user found'
        ], 401);
    }

}
