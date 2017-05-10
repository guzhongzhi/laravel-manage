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
Route::get('/sight', 'SightController@index');

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

