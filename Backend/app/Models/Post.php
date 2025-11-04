<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function page() {
        return $this->belongsTo(Page::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function postComment(){
        $auth_id = auth()->id();
        if($auth_id){
          $comment = $this->comments()->where('user_id',$auth_id)->first();
          if($comment){
            return $comment;
          }
        }

        return $this->comments()->first();
    }
}
