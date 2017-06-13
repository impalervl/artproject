<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    public function cities(){

        return $this->hasMany('App\City','cc_fips','cc_fips');
    }
}
