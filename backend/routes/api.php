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

Route::get('game-list', 'GameController@index');
Route::get('create-game', 'GameController@create');
Route::post('add-army', 'ArmyController@store');
Route::get('run-attack', 'GameController@runAttack');
Route::get('game-log','GameController@show');
Route::get('game-reset','GameController@reset');
