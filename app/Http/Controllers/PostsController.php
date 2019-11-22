<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Post;
use App\Http\Resources\Post as PostResource;
use App\RUser;
use Exception;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt');
    }


    // public function postToSubreddit(Post $post)
    // {
    //     $ruser = RUser::findOrFail($post['id']);
    //     $string = config('constants.redditApi');
    //     $result = shell_exec("python " . resource_path(). "\python\submitPost.py " . escapeshellarg($string['client_id']). " ". escapeshellarg($string['client_secret']). " ". escapeshellarg($ruser['token'])." " . escapeshellarg($post['subreddit']). escapeshellarg($post['title']). escapeshellarg($post['url']));
    //     return $result;
    // }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if($user->is_admin == 1){
            //Get posts
            $posts = Post::paginate(15);

            //Returne collection of posts as resouce
            return PostResource::collection($posts);
        }else{
            return response()->json(['message' => 'You need to be admin to get this information'], 403);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if($request->isMethod('put')){
            try{
                $post = Post::findOrFail($request->post_id);
            }catch(Exception $e){
                return response()->json(['message' => 'There is no such post'], 403);
            }
            if(!$post->canUse($user)){
                return response()->json(['message' => 'You need to be admin to get this information'], 403);
            }
        }
        $post = $request->isMethod('put') ? Post::findOrFail($request->post_id) : new Post;
        $post->id = $request->input('post_id');
        $post->subreddit = $request -> input('subreddit');
        $post->url = $request->input('url');
        $post->title = $request->input('title');
        $post->sr = $request->input('sr');
        $post->kind = $request->input('kind');
        $post->postTime = $request->input('postTime');
        $post->ruser_id = $request->input('ruser_id');
        $post ->save();
        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
        try
        {
            $post = Post::findOrFail($id);
        }catch(Exception $e){
            return response()->json(['message' => 'There is no such post'], 403);
        }
        
        if($post->canUse($user)){
            //Return single post as a resource
            return new PostResource($post);
        }else{
            return response()->json(['message' => 'You need to be admin to get this information'], 403);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();
        try{
            $post = Post::findOrFail($id);
        }catch(Exception $e){
            return response()->json(['message' => 'There is no such post'], 403);
        }
        if(!$post->canUse($user)){
            return response()->json(['message' => 'You need to be admin to get this information'], 403);
        }
        $post->delete();
        return new PostResource($post);
    }
}
