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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('todos', ['uses' => 'Api\TodoController@index']);
Route::post('todos', ['uses' => 'Api\TodoController@create']);
Route::put('todos/{id}', ['uses' => 'Api\TodoController@edit']);
Route::delete('todos/{id}', ['uses' => 'Api\TodoController@destroy']);
Route::put('todos/{id}/completed', ['uses' => 'Api\TodoController@markComplete']);