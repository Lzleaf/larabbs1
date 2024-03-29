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

Route::get('/', 'PagesController@root')->name('root');

Auth::routes();

Route::resource('users','UsersController',['only' => ['show','update','edit']]);
Route::resource('categories','CategoriesController',['only' => ['show']]);

Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::get('topics/{topic}/{slug?}', 'TopicsController@show')->name('topics.show');

Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');

Route::get('/test',function(){
    echo md5('123456789kboxing01');
});
Route::resource('replies', 'RepliesController', ['only' => ['store', 'destroy']]);

Route::resource('notifications', 'NotificationsController', ['only' => ['index']]);