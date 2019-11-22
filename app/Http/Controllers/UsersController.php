<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\User as UserResource;
use App\Ruser;
use App\Http\Resources\RUser as RUserResource;
use Hash;

class UsersController extends Controller
{
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt');
    }

    public function rusers()
    {
        $user = auth()->user();
        $rusers = RUser::where('user_id', $user['id'])->get();
        return new RUserResource($rusers);
    }


        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if($user->is_admin == 1){
        //Get usersphp artisan config:cache 
        $users = User::paginate(15);

        //Return collection of users as resouce
        return UserResource::collection($users);
        }

        return response()->json(['message' => 'You need admin permissions'], 403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userNow = auth()->user();
        if($userNow->is_admin == 1 || $request->isMethod('put')){
            $user = $request->isMethod('put') ? $userNow : new User;
//            $user->id = $userNow->id;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->is_admin = 0;
            if($userNow->is_admin == 1 ){
                $user->is_admin = 0;
                $user->is_admin = $request->input('is_admin');
                }
            if($user ->save()){
                return new UserResource($user);
            }
        }
        return response()->json(['message' => 'You need admin permissions'], 401);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userNow = auth()->user();
        if($userNow->is_admin == 1){
            //Get user
            $user = User::findOrFail($id);

            //Return single user as a resource
            return new UserResource($user);
        }
        return response()->json(['message' => 'You need admin permissions'], 403);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userNow = auth()->user();
        if($userNow->is_admin == 1 || $userNow->id == $id){
            //Get user
            $user = User::findOrFail($id);

            if ($user->delete()) {
                return new UserResource($user);
            }
        }
        return response()->json(['message' => 'You need admin permissions'], 403);
    }
}
