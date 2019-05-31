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
Route::post('threads/{channel}/{thread}/replies', 'ReplyController@store')->name('reply.store');
Route::get('threads/{channel}/{thread}/replies', 'ReplyController@index')->name('reply.index');
Route::post('replies/{reply}/favorites', 'FavoritesController@store')->name('favorite.reply.store');
Route::delete('replies/{reply}/favorites', 'FavoritesController@destroy')->name('favorite.reply.destroy');
Route::patch('replies/{reply}', 'ReplyController@update')->name('reply.update');
Route::delete('replies/{reply}', 'ReplyController@destroy')->name('reply.destroy');

Route::get('profiles/{user}', 'ProfilesController@show')->name('profiles.show');
