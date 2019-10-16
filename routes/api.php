<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//------------------Posts-------------------------------

//List posts
Route::get('posts','PostsController@index');

//List single post
Route::get('post/{id}','PostsController@show');

//Create new post
Route::post('post','PostsController@store');

//Update post
Route::put('post','PostsController@store');

//Delete post
Route::delete('post/{id}','PostsController@destroy');

//------------------RUser-------------------------------

//List rusers
Route::get('rusers','RUsersController@index');

//List single ruser
Route::get('ruser/{id}','RUsersController@show');

//List single ruser
Route::get('ruserposts/{id}','RUsersController@showPosts');

//Create new ruser
Route::post('ruser','RUsersController@store');

//Update ruser
Route::put('ruser','RUsersController@store');

//Delete ruser
Route::delete('ruser/{id}','RUsersController@destroy');

//------------------Groups-------------------------------

//List groups
Route::get('groups','GroupsController@index');

//List single group
Route::get('group/{id}','GroupsController@show');

//Create new group
Route::post('group','GroupsController@store');

//Update group
Route::put('group','GroupsController@store');

//Delete group
Route::delete('group/{id}','GroupsController@destroy');


