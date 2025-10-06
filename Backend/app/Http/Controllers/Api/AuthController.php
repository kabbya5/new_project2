<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\PhoneNumberVerificationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\TwilioService;
use App\Traits\FileUploadTrait;
use Carbon\Carbon;

class AuthController extends Controller
{
    use FileUploadTrait;
    private TwilioService $twilio;

    public function __construct(TwilioService $twilio) {
        $this->twilio = $twilio;
    }

    public function register(Request $request){

        $request->validate([
            'user_name' => 'required|string|min:3|unique:users,user_name',
            'refer_code' => 'nullable|string',
            'currency' => 'required|string',
            'email' => 'required|unique:users,email',
            'phone_number' => ['required', 'unique:users,phone', new PhoneNumberVerificationRule()],
            'password' => 'required|min:8|confirmed'
        ]);

        $refer_code = generate_random_key();

        $user = User::create([
            'name' => $request->user_name,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'currency' => $request->currency,
            'refer_code' => $refer_code,
            'phone' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('authentication')->plainTextToken;

        return response()->json([
            'message' => 'User registred successfully',
            'user' => $user,
            'token'  => $token,
        ]);

    }

    public function updateProfile(Request $request){
        $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = User::find(auth()->id());

        $imageUrl = null;
        $refer_code = generate_random_key();

        if ($request->hasFile('image')) {
            $this->deleteFile($user->image_url);
            $imageUrl = $this->storeFile($request->file('image'), 'profile', 400, 400);
        }

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone_number,
            'image_url' => $imageUrl,
            'refer_code' => $user->refer_code ?? $refer_code,
        ]);

        return response()->json([
            'message' => 'User registred successfully',
            'user' => $user,
        ]);
    }

    private function sendVerificationCode($phone)
    {
        $user = User::where('phone', $phone)->firstOrFail();

        // check if user is blocked
        if ($user->phone_verification_blocked_until && $user->phone_verification_blocked_until->isFuture()) {
            $minutes = $user->phone_verification_blocked_until->diffInMinutes(now());
            return response()->json(['message' => "Too many attempts. Try again after $minutes minutes"], 429);
        }

        $code = rand(100000, 999999);
        $user->phone_verification_code = $code;
        $user->phone_verification_expire_at = Carbon::now()->addMinutes(3);
        $user->phone_verification_attempts = 0; // reset attempts after sending new code
        $user->phone_verification_blocked_until = null;
        $user->save();

        $this->twilio->sendSMS($user->phone, "Your verification code is: $code. It will expire in 3 minutes.");

        return response()->json(['message' => 'Verification code sent']);
    }

    public function verifyCode(Request $request)
    {
        $user = User::where('phone', $request->phone)->firstOrFail();

        // check if user is blocked
        if ($user->phone_verification_blocked_until && $user->phone_verification_blocked_until->isFuture()) {
            $minutes = $user->phone_verification_blocked_until->diffInMinutes(now());
            return response()->json(['message' => "Too many attempts. Try again after $minutes minutes"], 429);
        }

        // check if code expired
        if ($user->phone_verification_expire_at && $user->phone_verification_expire_at->isPast()) {
            return response()->json(['message' => 'Verification code expired'], 400);
        }

        // check code
        if ($user->phone_verification_code != $request->code) {
            $user->phone_verification_attempts += 1;

            // block if 3 attempts
            if ($user->phone_verification_attempts >= 3) {
                $user->phone_verification_blocked_until = Carbon::now()->addMinutes(90);
                $user->phone_verification_attempts = 0; // reset attempts
            }

            $user->save();

            return response()->json(['message' => 'Invalid verification code'], 400);
        }

        // success
        $user->is_phone_verified = true;
        $user->phone_verification_code = null;
        $user->phone_verification_expire_at = null;
        $user->phone_verification_attempts = 0;
        $user->phone_verification_blocked_until = null;
        $user->save();

        return response()->json(['message' => 'Phone verified successfully']);
    }

    public function login(Request $request)
    {

        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->identifier)
                ->orWhere('user_name', $request->identifier)
                ->orWhere('phone', $request->identifier)
                ->first();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => ['Invalid credentials.']
                ]
            ], 422);
        }

        if ($user) {
            $token = $user->createToken('authentication')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
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

    public function me(){
        $auth_id = auth()->id();
        $user = User::find($auth_id);
        return response()->json([
            'user' => $user,
        ]);
    }
}
