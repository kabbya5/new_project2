<?php

namespace App\Http\Controllers;

use App\Events\MessageSendEvent;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function getMessage($receiver_id) {
        $user_id = auth()->id();

        $messages = Message::where(function ($query) use ($user_id, $receiver_id) {
            $query->where('sender_id', $user_id)->where('receiver_id', $receiver_id);
        })->orWhere(function ($query) use ($user_id, $receiver_id) {
            $query->where('sender_id', $receiver_id)->where('receiver_id', $user_id);
        })->orderBy('created_at', 'asc')->get();

        $receiver = User::find($receiver_id);

        return response()->json(['messages' => $messages,'receiver' => $receiver]);
    }

    public function pushMessage(Request $request, $receiver_id) {

        $user_id = auth()->id();
        if($request->hasFile('audio')){
            $path = $request->file('audio')->store('message/audio','public');
            $content = url(Storage::url($path));
            $message_type = 'audio';
        }

        $message = Message::create([
            'sender_id' => $user_id,
            'receiver_id' => $receiver_id,
            'content' => $content,
            'message_type' => $message_type,
        ]);

        broadcast(new MessageSendEvent($message,$receiver_id));

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function messageLists()
    {
        $user_id = auth()->id();

        $sql = "
            SELECT
                m1.created_at,
                m1.is_read,
                CASE
                    WHEN m1.message_type = 'text' THEN m1.content
                    ElSE m1.message_type
                END AS message,

                CASE
                    WHEN m1.sender_id = :user_id1 THEN u2.name
                    ELSE u1.name
                END AS receiver_name,

                CASE
                    WHEN m1.sender_id = :user_id2 THEN u2.id
                    ELSE u1.id
                END AS id,

                CASE
                    WHEN m1.sender_id = :user_id3 THEN u2.profile_picture
                    ELSE u1.profile_picture
                END AS receiver_image

            FROM messages m1
            JOIN users u1 ON u1.id = m1.sender_id
            JOIN users u2 ON u2.id = m1.receiver_id
            WHERE
                (m1.sender_id = :user_id4 OR m1.receiver_id = :user_id5)
                AND m1.id = (
                    SELECT MAX(m2.id)
                    FROM messages m2
                    WHERE
                        (m2.sender_id = m1.sender_id AND m2.receiver_id = m1.receiver_id)
                        OR
                        (m2.sender_id = m1.receiver_id AND m2.receiver_id = m1.sender_id)
            )
            ORDER BY m1.created_at DESC
        ";

        $message_lists = DB::select($sql, ['user_id1' => $user_id,'user_id2' => $user_id, 'user_id3' => $user_id, 'user_id4' => $user_id, 'user_id5' => $user_id]);

        return response()->json(['messages' => $message_lists]);
    }

    public function markAsRead($receiver_id){
        $user_id = auth()->id();

        $message = Message::where('sender_id', $user_id)
            ->where('receiver_id', $receiver_id)
            ->update(['is_read' => Carbon::now()]);

        // if ($message) {
        //     $updatedMessage = Message::where('sender_id', $user_id)
        //         ->where('receiver_id', $receiver_id)->latest()
        //         ->first();
        //     $readAt = Carbon::parse($updatedMessage->is_read)->diffForHumans();

        //     return response()->json(['status' => 'success', 'read_at' => $readAt]);
        // } else {
        //     return response()->json(['status' => 'error', 'message' => 'No messages found to update.']);
        // }

        return response()->json(['status' => 'success']);
    }

}
