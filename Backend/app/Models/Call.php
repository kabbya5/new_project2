<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    use HasFactory;

    protected $fillable = ['caller_id', 'receiver_id', 'group_id', 'call_type', 'status', 'started_at', 'ended_at'];

    // Caller of the call
    public function caller()
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    // Receiver of the call (null for group calls)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Group the call belongs to
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
