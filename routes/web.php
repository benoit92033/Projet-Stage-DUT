<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/addFriend', 'AmisController@addFriend');
Route::get('/joinFriend', 'AmisController@joinFriend');
Route::get('/delFriend', 'AmisController@delFriend');

Route::post('/morpion', 'MorpionController@morpion');
Route::get('/morpion', 'MorpionController@morpion');
Route::post('/puissance4', 'Puissance4Controller@puissance4');
Route::get('/puissance4', 'Puissance4Controller@puissance4');
Route::post('/batailleNavale', 'BatailleNavaleController@batailleNavale');
Route::get('/batailleNavale', 'BatailleNavaleController@batailleNavale');
