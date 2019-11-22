<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Group;
use App\Http\Resources\Group as GroupResource;
use Illuminate\Database\QueryException as QueryException;

class GroupsController extends Controller
{

    public function getRedditSubmissions($id)
    {
        $group = Group::findOrFail($id);
        $string = config('constants.redditApi');
        $result = shell_exec("python " . resource_path(). "\python\getSubreddits.py " . escapeshellarg($string['client_id']). " ". escapeshellarg($string['client_secret']). " ". escapeshellarg($string['redirect_uri'])." " . escapeshellarg($group['info']));
        return $result;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get groupsphp artisan config:cache 
        $groups = Group::paginate(15);

        //Return collection of groups as resouce
        return GroupResource::collection($groups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
        $group = $request->isMethod('put') ? Group::findOrFail($request->group_id) : new Group;
        $group->id = $request->input('group_id');
        $group->title = $request->input('title');
        $group->info = $request->input('info');
        $group ->save();
        }
        catch(QueryException $something){
            return response()->json(['message' => $something->getMessage()],400);
        }
        return new GroupResource($group);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Get group
        $group = Group::findOrFail($id);

        //Return single group as a resource
        return new GroupResource($group);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Get group
        $group = Group::findOrFail($id);
        $group->delete(); 
        return new GroupResource($group);
    }
}
