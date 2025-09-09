<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecenlyPlay extends Model
{
    protected $guarded = [];

    public function game(){
        return $this->belongsTo(Game::class);
    }
}
