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

Route::group(['middleware' => ['web']], function () {
    Route::get('/', [
    	'uses' => 'NiceactionController@getHome',
    	'as' => 'home'
    ]);

	Route::group(['prefix' => 'do'], function() {
		Route::get('/{action}/{name?}', [
			'uses' => 'NiceActionController@getNiceAction',
			'as' => 'niceaction'
		]);

		Route::post('/', [
			'uses' => 'NiceActionController@postInsertNiceAction',
			'as' => 'add_action'
		]);
	});
});
