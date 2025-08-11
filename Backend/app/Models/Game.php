<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $guarded = [];

    public function provider(){
        return $this->belongsTO(Provider::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function setEnglishNameAttribute($english_name)
    {
        $this->attributes['slug'] = str_slug($english_name);
        $this->attributes['english_name'] = $english_name;
    }
}
