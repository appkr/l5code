<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'commentable_type',
        'commentable_id',
        'user_id',
        'parent_id',
        'content',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
        'commentable_type',
        'commentable_id',
        'parent_id',
        'deleted_at',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'user',
        'votes'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'up_count',
        'down_count',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    /* Relationships */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id', 'id');
    }

    public function votes() {
        return $this->hasMany(Vote::class);
    }

    /* Accessors */

    public function getUpCountAttribute()
    {
        return (int) $this->votes()->sum('up');
    }

    public function getDownCountAttribute()
    {
        return (int) $this->votes()->sum('down');
    }
}
