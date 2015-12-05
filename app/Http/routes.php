<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::filter('checkUser', function ($request) {

    $user = App\User::where("name", "User_" .(int)$request->parameters()['id'])->first();
    if(!$user){
        return response()->json(['error' => 'No such user']);
    }
});

Route::group(array('before' => 'checkUser'), function()
{

    Route::get('/users/{id}', 'UserController@show')->where(['id' => '[0-9]+']);
    Route::get('/users/{id}/friends/{level?}', 'FriendsController@show')->where(['id' => '[0-9]+','level'=>'[0-9]+']);
    Route::delete('/users/{id}/friends/{to}', 'FriendsController@destroy')->where(['id' => '[0-9]+','to'=>'[0-9]+']);
    Route::get('/users/{id}/requests/my', 'RequestsController@show')->where(['id' => '[0-9]+']);
    Route::get('/users/{id}/requests/me', 'RequestsController@income')->where(['id' => '[0-9]+']);
    Route::put('/users/{id}/requests/{to}', 'RequestsController@create')->where(['id' => '[0-9]+','to'=>'[0-9]+']);
    Route::delete('/users/{id}/requests/{to}', 'RequestsController@destroy')->where(['id' => '[0-9]+','to'=>'[0-9]+']);
});

