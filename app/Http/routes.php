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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware'=>'auth'], function() {

    Route::get('/comments/{url}', '\PhpSoft\Comments\Controllers\CommentController@index')->where('url', '.*');
    Route::post('/comments/{url}', '\PhpSoft\Comments\Controllers\CommentController@store')->where('url', '.*');
    Route::patch('/comments/{id}', '\PhpSoft\Comments\Controllers\CommentController@update');
    Route::delete('/comments/{id}', '\PhpSoft\Comments\Controllers\CommentController@destroy');
});
