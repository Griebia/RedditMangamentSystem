<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\RUser;
use App\Post;
use App\Http\Resources\RUser as RUserResource;
use App\Http\Resources\Post as PostResource;
use Exception;
use Hash;



class RUsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt');
    }

    public function getCodeURL($state)
    {
        $string = config('constants.redditApi');
        $result = shell_exec("python " . resource_path(). "\python\getAuthorizationURL.py " . escapeshellarg($string['client_id']). " ". escapeshellarg($string['client_secret']). " ". escapeshellarg($string['redirect_uri'])." " . escapeshellarg($state));
        return $result;
    }

    // public function getToken($code)
    // {
    //     $string = config('constants.redditApi');
    //     $result = shell_exec("python " . resource_path(). "\python\getToken.py " . escapeshellarg($string['client_id']). " ". escapeshellarg($string['client_secret']). " ". escapeshellarg($string['redirect_uri']). " " . escapeshellarg($code));
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
            //Get rusers
            $rusers = RUser::paginate(15);

            //Returne collection of rusers as resouce
            return RUserResource::collection($rusers);
        }
        return response()->json(['message' => 'You need to be admin to get this information'], 403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        $ruser = new RUser;
        $ruser->id = $request->input('ruser_id');
        $ruser->username = $request->input('username');
        $ruser->password = Hash::make($request->input('password'));
        $ruser->user_id = $user['id'];
        $ruser->saveOrFail();
        $url = $this->getCodeURL($request->input('ruser_id'));
        return new RUserResource($ruser);
    }

    // public function setToken()
    // {
    //     if(isset($_GET["code"]))
    //     {
    //         $ruser = Ruser::findOrFail($_GET["state"]);
    //         $code = $_GET["code"];
    //         $token = $this->getToken($code);
    //         $ruser->token = $token;
    //         $ruser->save();
    //         return "entered";
    //     }   
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $ruser = Ruser::findOrFail($request->input('ruser_id'));
        if($user->is_admin == 1 || $user->id == $ruser->user_id){
            $ruser->username = $request->input('username');
            $ruser->password = Hash::make($request->input('password'));
            $ruser->token = $request->input('token');
            if($ruser ->save()){
                return new RUserResource($ruser);
            }
        }
        return response()->json(['message' => 'You need to be admin to get this information'], 403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Get ruser
        $ruser = RUser::findOrFail($id);
        $user = auth()->user();

        if($user->is_admin == 1 || $user->id == $ruser->user_id){
            //Get ruser
            $ruser = RUser::findOrFail($id);

            //Return single ruser as a resource
            return new RUserResource($ruser);
        }
        return response()->json(['message' => 'You need to be admin to access this information'], 403);   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPosts($id)
    {
        $ruser = RUser::findOrFail($id);
        $user = auth()->user();

        if($user->is_admin == 1 || $user->id == $ruser->user_id)
        {
            $posts = Post::where('ruser_id', $id)->get();

            if ($posts->isEmpty()) 
            { 
                return response()->json(['message' => 'There are none posts with this id'], 403);
            }
            else
            {
                return new PostResource($posts);
            }
        }
        return response()->json(['message' => 'You need to be admin to access this information'], 403);
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
            $ruser = RUser::findOrFail($id);
        }catch(Exception $e){
            return response()->json(['message' => 'There is no such post'], 403);
        }
        if(!$user->is_admin == 1 || !$user->id == $ruser->user_id){
            return response()->json(['message' => 'You need to be admin to get this information'], 403);
        }
        $ruser->delete();
        return new RuserResource($ruser);
    }
}
