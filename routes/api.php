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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'api'], function () {
    /** บุคลากร */
    Route::get('persons', 'PersonController@getAll');
    Route::get('persons/{id}', 'PersonController@getById');
    Route::put('persons/{id}/move', 'PersonController@move');
    Route::put('persons/{id}/transfer', 'PersonController@transfer');
    Route::put('persons/{id}/leave', 'PersonController@leave');
    Route::put('persons/{id}/status', 'PersonController@status');
    Route::put('persons/{id}/rename', 'PersonController@rename');
    Route::get('persons/{id}/movings', 'PersonController@getMoving');
});