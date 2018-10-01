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
    // echo thumb_onthefly_Article("1536.jpg", 300, 200, 'auto');
   //https://cdn1.katadata.co.id/media/images/thumb/2018/02/01/2018_02_01-14_51_57_11bb0fef5104ea673a034e7f0b9744d6.jpg
    echo thumb_image('2018/02/01/2018_02_01-14_51_57_11bb0fef5104ea673a034e7f0b9744d6.jpg','620x413shared1');
});
Route::group(['middleware' => 'auth:api'], function(){
	Route::post('content', ['uses' => 'API\ContentController@index']);
});

