<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\RUser;
use App\Post;
use App\Http\Resources\RUser as RUserResource;
use App\Http\Resources\Post as PostResource;
use Exception;

class RUsersController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get rusers
        $rusers = RUser::paginate(15);

        //Returne collection of rusers as resouce
        return RUserResource::collection($rusers);
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
        //
        $ruser = $request->isMethod('put') ? RUser::findOrFail($request->ruser_id) : new RUser;
        $ruser->id = $request->input('ruser_id');
        $ruser->username = $request->input('username');
        $ruser->password = $request->input('password');
        $ruser->token = $request->input('token');
        if($ruser ->save()){
            return new RUserResource($ruser);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            //Get ruser
            $ruser = RUser::findOrFail($id);

            //Return single ruser as a resource
            return new RUserResource($ruser);
    }
        catch(Exception $something){
            return ['message' => $something->getMessage()];
        }
            
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPosts($id)
    {
        $posts = Post::where('ruser_id', $id)->get();

        if ($posts->isEmpty()) 
        { 
            abort(404);
        }
        else
        {
            return new PostResource($posts);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Get ruser
        $ruser = RUser::findOrFail($id);

        if ($ruser->delete()) {
            return new RUserResource($ruser);
        }
    }
}
