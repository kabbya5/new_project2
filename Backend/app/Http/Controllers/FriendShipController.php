<?php

namespace App\Http\Controllers;

use App\Events\FriendRequestEvent;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FriendShipController extends Controller
{
    public function getRequestedFriend(){
        $user_id = auth()->id();

        $friend_requests = DB::select("
            SELECT
                u.name,
                u.profile_picture,
                u.id
            FROM users as u
            JOIN friendships as f on f.user_id = u.id
            WHERE f.friend_id = {$user_id}
            AND f.status = 'pending'
            ORDER BY f.created_at DESC
        ");

        return response()->json(['friend_request'  => $friend_requests]);
    }


    public function getFindFriends(Request $request){
        $limit = $request->input('limit', 30);

        $find_friends = User::whereDoesntHave('friendshipsAsUser', function ($query) {
            $query->where('friend_id', Auth::id());
        })
        ->whereDoesntHave('friendshipsAsFriend', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->where('id', '!=', Auth::id()) // Exclude the logged-in user
        ->paginate($limit);

        return response()->json(['fiend_friends' => $find_friends->items()], 200);
    }


    public function addFriend($friendId){
        $user = Auth::user();

        if($user->id === $friendId){
            return response()->json(['message' => 'You cannot add yourself'], 400);
        }

        $existtingRequest = DB::select("
            SELECT * FROM friendships
            where(user_id = :user_id AND friend_id = :friend_id)
                OR(user_id = :friend_id AND friend_id = :user_id)
            LIMIT 1;
        ",['user_id'  => $user->id, 'friend_id' => $friendId]);

        if($existtingRequest){
            return response()->json(['message' => 'Friend request already exists or you are already friends.'], 400);
        }

        DB::statement("INSERT INTO friendships (user_id, friend_id, status)
            VALUES(?, ?, ?)", [$user->id, $friendId, 'pending']);

        return response()->json(['message' => 'Friend request sent.'], 200);
    }

    public function getFriendStatus(User $friend){
        $user_id = auth()->id();
        $status = Friendship::where('user_id', $user_id)
            ->where('friend_id', $friend->id)
            ->orWhere('friend_id', $user_id)
            ->where('user_id', $friend->id)
            ->first();


        if (!$status) {
            return response()->json(['friend' => $friend, 'status' => 'not_friends']);
        }

        return response()->json(['friend' => $friend, 'status' => $status->status]);
    }

    public function requestSend($friend_id){
        $user_id = auth()->id();

        $status = Friendship::where('user_id', $user_id)
            ->where('friend_id', $friend_id)
            ->orWhere('friend_id', $user_id)
            ->where('user_id', $friend_id)
            ->first();

        if ($status) {
            return response()->json(['status' => $status->status], 200);
        }

        $sender = auth()->user()->only(['id', 'name', 'email','profile_picture']);

        Broadcast(new FriendRequestEvent($friend_id, $sender));

        Friendship::create([
            'friend_id' => $friend_id,
            'user_id'   => $user_id,
            'status'    => 'pending',
        ]);

        return response()->json(['status' => 'pending'], 200);
    }

    public function confirm_friend(Request $request,$friend_id){
        $friend = Friendship::where('user_id', $friend_id)->where('friend_id', auth()->id())->first();
        if(!$friend){
            return response()->json(['error' => 'Friend not found'], 401);
        }

        $friend->update(['status' => 'accepted']);

        return response()->json(['success' => 'The friend confirm'],200);
    }

    public function cancleRequest($friend_id){
        $friend = Friendship::where('user_id', auth()->id())
        ->where('friend_id', $friend_id)
        ->first();

        if (!$friend) {
            return response()->json(['status' => false], 200);
        }

        $friend->delete();

        return response()->json(['status' => false], 200);
    }

    public function deleteRequest($friend_id){
        $friend = Friendship::where('user_id',$friend_id)
        ->where('friend_id', auth()->id())
        ->first();

        if (!$friend) {
            return response()->json(['status' => false], 200);
        }

        $friend->update(['status' => 'declined']);

        return response()->json(['status' => false], 200);
    }

    public function onlineFriends(){
        $user_id = auth()->id();
        $fiveMinutesAgo = Carbon::now()->subMinutes(10);
        $online_friends = User::where('is_online', '>=', $fiveMinutesAgo)
                      ->where('id', '!=', $user_id)
                      ->get();

        return response()->json(['friends' =>$online_friends]);
    }
}
