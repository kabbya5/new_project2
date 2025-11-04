<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'created_by'];

    // Creator of the group
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Members of the group
    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members');
    }

    // Messages in the group
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
