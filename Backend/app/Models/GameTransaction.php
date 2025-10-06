<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameTransaction extends Model
{
    protected $guarded = [];

    public function game(){
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
