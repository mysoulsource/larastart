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

Route::get('/home', 'HomeController@index')->name('home');

/// while using vue routes.
////If user enetered the url. that will search for laravel routes. To solve this we use below route,
Route::get('{path}',"HomeController@index")->where( 'path', '([A-z\d-\/_.]+)?' );
//end
