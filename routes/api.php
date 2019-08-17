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

// List the articles
Route::post('article', 'ArticlesController@store')->name('articles.save');
Route::put('article', 'ArticlesController@store')->name('articles.update');


Route::get('articles', 'ArticlesController@index')->name('articles.index');
Route::get('article/{id}', 'ArticlesController@show')->name('articles.show');

Route::delete('article/{id}', 'ArticlesController@destroy')->name('articles.destroy');