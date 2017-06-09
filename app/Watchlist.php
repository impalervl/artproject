<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    protected $fillable = ['user_id','picture_id'];

    public function user(){

        $this->belongsTo('App\User');
    }

    public function picture(){

        $this->hasMany('App\Picture');
    }
}
