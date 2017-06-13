<?php

namespace App\Http\Controllers;

use App\City;
use App\County;
use Illuminate\Http\Request;

class CityCountryController extends Controller
{
    public function getCities(Request $request){

        $country = $request->country;
        $country = County::where('cc_fips', '=', $country)->first();
        $cities = $country->cities->all();
        return response()->json(['cities'=>$cities],200);
    }

    public function getCountries(Request $request){

        $countries = County::all();
        return response()->json(['countries'=>$countries],200);
    }
}
