<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'comment_id',
        'up',
        'down',
        'voted_at',
    ];

    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = [
        'user_id',
        'up',
        'down',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'voted_at',
    ];

    /* Relationships */

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* Mutators */

    public function setUpAttribute($value)
    {
        $this->attributes['up'] = $value ? 1 : null;
    }

    public function setDownAttribute($value)
    {
        $this->attributes['down'] = $value ? 1 : null;
    }
}
