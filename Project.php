<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['full_name', 'short_name', 'icon_color', 'url', 'page_image', 'is_work'];
    protected $table = 'projects';
    public $timestamps = false;

    //Достаем из базы id всех активных проектов используя Eloquent + Serializing To Arrays
    public static function getActiveProjects()
    {
        return self::where('is_work',true)->select('id')->get()->toArray();
    }


}
