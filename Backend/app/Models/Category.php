<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['english_name', 'bangla_name', 'hindi_name', 'image_url', 'position'];

    public function providers(){
        return $this->belongsToMany(Provider::class,'category_provider','category_id','provider_id');
    }

    public function games()
    {
        return $this->belongsToMany(Game::class,'category_game','category_id','game_id');
    }

    public function setEnglishNameAttribute($english_name)
    {
        $this->attributes['slug'] = str_slug($english_name);
        $this->attributes['english_name'] = $english_name;
    }
}
