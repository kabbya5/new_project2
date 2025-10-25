<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserMangeController extends Controller
{
    public function findUser(User $user){
        return response()->json(['data' => new UserResource($user)]);
    }

    public function update(Request $request, User $user){
        $request->validate([
            'name' => 'required|min:3|max:40',
            'email' => 'required|email',
            'role' => 'required',
            'password' => 'nullable|min:8'
        ]);

        $user->update($request->except('passwod'));

        $password = $request->password;
        if($password){
            $user->update(['password' => Hash::make($password)]);
        }

        return true;
    }
}
