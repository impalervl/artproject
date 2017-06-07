<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;


class User extends Authenticatable
{

    use Notifiable;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function pictures(){

        return $this->hasMany('App\Picture');
    }

    public function followers()
    {
        return $this->belongsToMany(
            self::class,
            'follows',
            'followee_id',
            'follower_id'
        );
    }

    public function followees()
    {
        return $this->belongsToMany(
            self::class,
            'follows',
            'follower_id',
            'followee_id'
        );
    }

    public function watchlist(){

        return $this->belongsToMany(
            'App\Picture',
            'picture_user',
            'user_id',
            'picture_id'
            );
    }


}
