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
    return redirect('/shop');
});

Route::get('/shop/{id}/robot/{rid}', function() { 
	abort(404); 
});

Route::delete('/shop/{id}/robot/{rid}', 'HomeController@robot_destroy');
Route::post('/shop/{id}/robot', 'HomeController@robot_store');
Route::post('/shop/{id}/execute','HomeController@execute');
Route::resource('/shop', 'HomeController');
