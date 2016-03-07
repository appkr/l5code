<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'confirm_code',
        'activated',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'confirm_code',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_login'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'activated' => 'boolean',
    ];

    /* Relationships */

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /* Query Scopes */

    public function scopeSocialUser($query, $email)
    {
        return $query->whereEmail($email)->where('password', '')->orWhereNull('password');
    }

    /* Accessor */

    public function getGravatarUrlAttribute()
    {
        return sprintf("//www.gravatar.com/avatar/%s?s=%s", md5($this->email), 48);
    }

    /* Helpers */

    public function isAdmin()
    {
        return ($this->id === 1) ? true : false;
    }
}
