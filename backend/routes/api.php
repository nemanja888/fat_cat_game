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

Route::get('create-game', 'GameController@store');
Route::post('add-army', 'ArmyController@store');
Route::get('game-log/{id}','GameController@show');
Route::get('game-reset/{id}','GameController@reset');
Route::get('game/{gameId}/run-attack/army/{armyId}', 'GameController@runAttack');
Route::get('game-list', 'GameController@index');
Route::get('army/{id}/show', 'ArmyController@show');
