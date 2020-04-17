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
    //\Log::debug('Test debug message');
     App::setLocale('ru');
    return view('main');
})->name('main');

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::resource('users', 'UserController', ['except' => [
    'create', 'store'
]]);

Route::resource('tasks', 'TaskController');

Route::resource('task_statuses', 'TaskStatusController', ['except' => [
    'edit', 'update'
]]);
