<?php

use Illuminate\Http\Request;

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

Auth::routes(['verify' => true]);

Route::post('/auth/register', 'AuthController@register');
Route::post('/auth/login', 'AuthController@login');

Route::group(['middleware' => 'jwt.auth'], function(){
    Route::get('auth/user', 'AuthController@user');
    Route::post('auth/logout', 'AuthController@logout');
});
Route::group(['middleware' => 'jwt.refresh'], function(){
    Route::get('auth/refresh', 'AuthController@refresh');
});

Auth::routes();

Route::group(['middleware' => 'jwt.auth'], function() {
    Route::get('/contacts/get', 'ContactsController@index');
    Route::post('/contacts/add', 'ContactsController@add');
    Route::delete('/contacts/delete/{id}', 'ContactsController@delete');
    Route::put('/contacts/edit/{id}', 'ContactsController@edit');
});
