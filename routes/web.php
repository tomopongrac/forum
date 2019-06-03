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

Route::post('threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->name('thread.subscription.store')->middleware('auth');
Route::delete('threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->name('thread.subscription.store')->middleware('auth');

Route::get('profiles/{user}', 'ProfilesController@show')->name('profiles.show');
Route::get('profiles/{user}/notifications', 'UserNotificationsController@index')->name('user.notifications.index');
Route::delete('profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy')->name('user.notifications.destroy');
