<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('notification',function(){
    return true;
});

Broadcast::channel('post.like', function(){
    return true;
});

Broadcast::channel('post.comment', function(){
    return true;
});

Broadcast::channel('friend_request-{friend_id}', function ($user, $friend_id) {
    return (int) $user->id === (int) $friend_id;
});

Broadcast::channel('message.receiver.{receiver_id}', function($user, $receiver_id) {
    return (int) $user->id === (int) $receiver_id;
});

