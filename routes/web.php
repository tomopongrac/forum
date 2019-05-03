<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::resource('threads', 'ThreadController')->except(['show']);
Route::get('threads/{channel}', 'ThreadController@index')->name('threads.channel');
Route::get('threads/{channel}/{thread}', 'ThreadController@show')->name('threads.show');

Route::post('threads/{channel}/{thread}/reply', 'ReplyController@store')->name('reply.store');
