<?php

namespace App\Http\Controllers;

use App\Picture;
use Illuminate\Http\Request;

class PictureController extends Controller
{

    /*public function __construct()
    {
        $this->middleware('jwt.auth',['except' => ['index','show']]);
    }*/
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pictures = Picture::all();

        foreach ($pictures as $picture){

            $response[$picture->name]['picture'] = $picture;
            $response[$picture->name]['author'] = $picture->user;
        }

        return response()->json($response,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $picture = new Picture;

        $data['description'] = $request->description;
        $data['user_id'] = $request->user_id;
        $data['location'] = $request->location;
        $data['cost'] = $request->cost;
        $data['name'] = $request->name;
        $data['likes'] = $request->likes;

        $picture = $picture->create($data);

        return response()->json(['picture' => $picture], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function show(Picture $picture)
    {
        $response['user'] = $picture->user->name;
        $response['user_id'] = $picture->user->id;
        $response['picture'] = $picture;
        $response['other_pictures'] = Picture::where('user_id','=',$picture->user->id)->get();

        return response()->json($response,200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function edit(Picture $picture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Picture $picture)
    {
        $content = Picture::find($picture->id);

        if(!$content){
            return response()->json(['message'=>'Document not found'],404);
        }

        $data['description'] = $request->description;
        $data['user_id'] = $request->user_id;
        $data['location'] = $request->location;
        $data['cost'] = $request->cost;
        $data['name'] = $request->name;
        $data['likes'] = $request->likes;

        $content->update($data);

        return response()->json(['picture' => $content],200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function destroy(Picture $picture)
    {
        $picture->delete();

        return response()->json(['message'=>'picture deleted'],200);
    }
}
