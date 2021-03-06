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

Route::get('/', 'WelcomeController@index');

Route::get('/hotel', 'HotelController@index');
Route::get('/hotel/s-{id}.html', 'HotelController@hotelDetail');
Route::get('/hotel/p{id}.html', 'HotelController@provinceList');
Route::get('/hotel/c{id}.html', 'HotelController@cityList');

Route::get('/sight', 'SightController@index');
Route::get('/sight/p{id}.html', 'SightController@provinceList');
Route::get('/sight/c{id}.html', 'SightController@cityList');
Route::get('/sight/s-{id}.html', 'SightController@sightDetail');

Route::get('/travel', 'TravelController@index');
Route::get('/travel/p{id}.html', 'TravelController@provinceList');
Route::get('/travel/c{id}.html', 'TravelController@cityList');
Route::get('/travel/s-{id}.html', 'TravelController@travelDetail');
Route::get('/travel/like', 'TravelController@travelEnjoy');


Route::get('/food', 'FoodController@index');
Route::get('/food/p{id}.html', 'FoodController@provinceList');
Route::get('/food/c{id}.html', 'FoodController@cityList');
Route::get('/food/d-{id}.html', 'FoodController@foodDetail');
Route::get('/food/c{id}.html', 'FoodController@cityList');
Route::get('/food/like', 'FoodController@foodEnjoy');
Route::get('/store/like', 'FoodController@storeEnjoy');
Route::get('/store/d-{id}.html', 'FoodController@storeDetail');

Route::group(['prefix' => 'admin','namespace'=>'Admin'], function () {
    Route::group(["as"=>"admin.welcome"],function() {
        Route::get('', function(){
            header("Location: /admin/dashboard" );
            die();
        });
        Route::get('login', 'WelcomeController@login');
        Route::get('logout', 'DashboardController@logout');
        Route::post('loginPost', 'WelcomeController@loginPost');
    });

    Route::get('system/menu', array(
        'as'=>"admin.system.menu",
        "uses"=>'SystemMenuController@index',
    ));

    Route::get('system/menu/edit/{id}', array(
        'as'=>"admin.system.menu",
        "uses"=>'SystemMenuController@edit',
    ));

    Route::get('system/configuration', array(
        'as'=>"admin.system.configuration",
        "uses"=>'SystemMenuController@configuration',
    ));



    Route::get('dashboard', array(
        'as'=>"admin.dashboard",
        "uses"=>'DashboardController@index',
    ));
    

});

