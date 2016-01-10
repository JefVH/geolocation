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

Route::get('/', function () {
    return view('home');
});

Route::get('tracks', ['as' => 'tracks', 'uses' => 'TrackController@index']);
Route::get('tracks/new', ['as' => 'new_track', 'uses' => 'TrackController@create']);
Route::post('tracks/new', ['as' => 'store_track', 'uses' => 'TrackController@store']);
Route::get('tracks/{id}/view', ['as' => 'view_track', 'uses' => 'TrackController@show']);
Route::get('tracks/{id}/coordinates', ['as' => 'gettrackcoordinates', 'uses' => 'TrackController@getCoordinates']);
Route::post('tracks/{id}/coordinates', ['as' => 'savetrackcoordinates', 'uses' => 'TrackController@saveCoordinates']);

Route::get('publictransport', ['as' => 'public_transport', 'uses' => 'PublicTransportController@index']);
Route::get('processcoordinates', ['as' => 'process_coordinates', 'uses' => 'PublicTransportController@processCoordinates']);
