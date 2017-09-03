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
        'ko',
        'en',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
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
