<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::get('/data/default_dtd', 'DataController@default_dtd');
Route::post('/data/generate', 'DataController@generate');
Route::post('/data/savexml', 'DataController@savexml');
Route::get('/data/showsave', 'DataController@showsave');
Route::post('/data/deletexml', 'DataController@deletexml');

Route::group(['middleware' => ['web']], function () {
  Route::get('auth/login', 'Auth\AuthController@getLogin');
  Route::post('auth/login', 'Auth\AuthController@postLogin');
  Route::get('auth/logout', 'Auth\AuthController@logout');

  Route::get('auth/register', 'Auth\AuthController@getRegister');
  Route::post('auth/register', 'Auth\AuthController@postRegister');

  Route::get('/', 'HomeController@index');
  Route::get('profile', 'ProfileController@index');
});