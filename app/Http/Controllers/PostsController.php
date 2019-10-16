<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Post;
use App\Http\Resources\Post as PostResource;

class PostsController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get posts
        $posts = Post::paginate(15);

        //Returne collection of posts as resouce
        return PostResource::collection($posts);
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
        $post = $request->isMethod('put') ? Post::findOrFail($request->post_id) : new Post;
        $post->id = $request->input('post_id');
        $post->url = $request->input('url');
        $post->title = $request->input('title');
        $post->sr = $request->input('sr');
        $post->kind = $request->input('kind');
        $post->postTime = $request->input('postTime');
        $post->ruser_id = $request->input('ruser_id');
        if($post ->save()){
            return new PostResource($post);
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
        //Get post
        $post = Post::findOrFail($id);

        //Return single post as a resource
        return new PostResource($post);
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
        //Get post
        $post = Post::findOrFail($id);

        if ($post->delete()) {
            return new PostResource($post);
        }
    }
}
