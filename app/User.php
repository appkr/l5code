<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

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
        'notification',
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
        'last_login',
        'activated',
        'updated_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'last_login',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'activated' => 'boolean',
    ];

    /* Relationships */

    public function articles() {
        return $this->hasMany(Article::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /* Query Scopes */

    public function scopeSocialUser(\Illuminate\Database\Eloquent\Builder $query, $email)
    {
        return $query->whereEmail($email)->whereNull('password')->whereActivated(1);
    }

    /* Accessors */

//    public function getGravatarUrlAttribute()
//    {
//        return sprintf("//www.gravatar.com/avatar/%s?s=%s", md5($this->email), 48);
//    }

    /* Helpers */

    public function isAdmin()
    {
        return $this->id === 1;
    }

    public function isSocialUser()
    {
        return is_null($this->password) && $this->activated;
    }
}
