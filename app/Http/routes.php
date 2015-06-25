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

// farhan-website routes
Route::group(['domain' => 'www.farhanali.in'], function() {

    Route::get('/', function() {
        return response()->view('farhan-website.index');
    });

});

// mock server routes
Route::group(['domain' => 'mock.restboat.com'], function() {

    Route::any('{userIdentifier}/{all?}', array(
        'as'   => 'api.log',
        'uses' => 'RestMockController@mockRequest'
    ))->where('all', '.*');

    Route::get('/', function() {
        abort(404);
    });

});

// Public routes
Route::get('/', array(
    'as' => 'index',
    'uses' => 'PagesController@index'
));

Route::get('tips', array(
    'as' => 'tips',
    'uses' => 'PagesController@tips'
));

Route::get('user/login/{provider?}', array(
    'as' => 'user.login',
    'uses' => 'AuthController@login'
));

// routes require authentication
Route::group(['middleware' => 'auth'], function() {

    // User related routes - after login
    Route::get('user/logout', array(
        'as' => 'user.logout',
        'uses' => 'AuthController@logout'
    ));

    Route::get('user/preferences', array(
        'as' => 'user.preferences',
        'uses' => 'UserPreferenceController@show'
    ));

    Route::put('user/preferences/update', array(
        'as' => 'user.preferences.update',
        'uses' => 'UserPreferenceController@update'
    ));

    Route::get('user/preferences/token/update', array(
        'as' => 'user.preferences.token.update',
        'uses' => 'UserPreferenceController@updateToken'
    ));

    // Collections related routes
    Route::resource('collections', 'CollectionsController', ['only' => ['index', 'show', 'store']]);

    Route::delete('collections/{id?}', array(
        'as' => 'collections.destroy',
        'uses' => 'CollectionsController@destroy'
    ));

    // History related routes
    Route::resource('history', 'HistoryController', ['only' => ['index', 'show', 'store']]);

    Route::delete('history/{id?}', array(
        'as' => 'history.destroy',
        'uses' => 'HistoryController@destroy'
    ));

    // Response related routes
    Route::resource('response', 'ResponseController', ['only' => ['update']]);

});


