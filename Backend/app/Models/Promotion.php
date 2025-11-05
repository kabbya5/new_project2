<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $guarded = [];

    public function setTitleAttribute($title)
    {
        $this->attributes['slug'] = str_slug($title);
        $this->attributes['title'] = $title;
    }
}
