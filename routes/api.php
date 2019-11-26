<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

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

Route::group([

    'prefix' => 'auth'

], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});
Route::post('register', 'AuthController@register');

//------------------User-------------------------------

//Create new user
Route::post('user','UsersController@store');
//Show all of the users
Route::get('users','UsersController@index');
//Upadate user
Route::put('user','UsersController@store');
//Show user
Route::get('user/{id}','UsersController@show');
//Delete post
Route::delete('user/{id}','UsersController@destroy');

Route::get('userrusers','UsersController@rusers');


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

//List of all rusers
Route::get('rusers','RUsersController@index');
//List of rusers that writen to currrent user
Route::get('rusersme','UsersController@rusers');
//Creating an ruser and returning the url for connection.
Route::post('connect','RUsersController@create');
//Getting the code o
Route::get('getcode','RUsersController@setToken');
//List single ruser
Route::get('ruser/{id}','RUsersController@show');

//List single ruser
Route::get('ruserposts/{id}','RUsersController@showPosts');

//Create new ruser
//Route::post('ruser','RUsersController@store');

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

//Get reddit info from group
Route::get('groupreddit/{id}','GroupsController@getRedditSubmissions');


