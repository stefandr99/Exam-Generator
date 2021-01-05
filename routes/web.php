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
Route::get('/users', 'UserController@showAll')->name('users');
Route::put('/users/update/{id}/newRole={newRole}', 'UserController@updateUserRole')->name('update_role');
Route::get('/exam', 'ExamController@generate')->name('generate_exam');
Route::post('/exam/result', 'ExamController@correctPartial')->name('correct_partial');
