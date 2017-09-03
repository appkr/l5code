<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filename',
        'bytes',
        'mime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'article_id',
        'created_at',
        'updated_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'url',
    ];

    /* Relationships */

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /* Accessors */

    public function getBytesAttribute($value)
    {
        return format_filesize($value);
    }

    public function getUrlAttribute()
    {
        return url('files/'.$this->filename);
    }
}
