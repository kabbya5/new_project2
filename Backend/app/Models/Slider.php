<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $guarded = [];

    public function setSliderNameAttribute($slider_name)
    {
        $this->attributes['slug'] = str_slug($slider_name);
        $this->attributes['slider_name'] = $slider_name;
    }
}
