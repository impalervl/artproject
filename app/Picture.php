<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $fillable = ['user_id','description','location','cost','name','likes'];

    public function user(){

        return $this->belongsTo('App\User');
    }

    public function watchlistUser(){

        return $this->belongsToMany('App\User');
    }
}
