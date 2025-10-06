<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function affiliat_agent(){
        return $this->belongsTo(User::class, 'affiliate_refer_id');
    }

    public function refer_user(){
        return $this->belongsTo(User::class, 'other_refer_id');
    }
}
