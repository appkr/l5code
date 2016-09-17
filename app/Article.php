<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'notification',
        'view_count',
        'notification',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
        'notification',
        'deleted_at',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'user',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
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

    /* Accessor */

    public function getCommentCountAttribute() {
        return (int) $this->comments->count();
    }

//    // 의사(Pseudo) 코드. 주석 풀어도 작동하지 않습니다.
//    public function getCreatedAtAttribute($value)
//    {
//        $timezone = (auth()->user()->timezone) ?: config('app.timezone');
//        $datetime = $this->asDateTime($value);
//
//        return $datetime->timezone($timezone);
//    }
}
