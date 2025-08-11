<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $guarded = [];

    public function categories(){
        return $this->belongsToMany(Category::class,'category_provider','provider_id','category_id');
    }

    public function games(){
        return $this->hasmany(Game::class);
    }

    public function setEnglishNameAttribute($english_name)
    {
        $this->attributes['slug'] = str_slug($english_name);
        $this->attributes['english_name'] = $english_name;
    }
}
