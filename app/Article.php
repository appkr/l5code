<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'user',
    ];

    /* Relationships */

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    public function attachments() {
        return $this->hasMany(Attachment::class);
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
