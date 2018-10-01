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
Route::get('/baru', function () {
    echo thumb_onthefly_Article("991.jpg", 300, 200, 'auto');
});
Route::group(['middleware' => 'auth:api'], function(){
	Route::post('content', ['uses' => 'API\ContentController@index']);
});

