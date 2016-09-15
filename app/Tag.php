<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /* Relationships */

    public function articles() {
        return $this->belongsToMany(Article::class);
    }

    /* Accessors */

//    public function getArticlesCountAttribute()
//    {
//        return $this->articles->count();
//    }
}
