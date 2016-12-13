<?php

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

Route::group(['middleware' => 'cors'], function(){
    Route::post('login', 'Api\AuthController@login');
    Route::post('refresh_token', 'Api\AuthController@refreshToken');

    Route::post('users', 'Api\UsersController@store');

    Route::group(['middleware' => ['jwt.auth', 'tenant'] ], function () {
        Route::post('logout', 'Api\AuthController@logout');
        Route::resource('categories', 'Api\CategoriesController', ['except' => ['create', 'edit']]);
        Route::get('bill_pays/total', 'Api\BillPaysController@calculateTotal');
        Route::resource('bill_pays', 'Api\BillPaysController', ['except' => ['create', 'edit']]);
    });
});

