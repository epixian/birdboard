<?php

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

Route::group(['middleware' => 'auth'], function () {
    // Projects
    Route::resource('projects', 'ProjectsController');

    // Project Tasks
    Route::post('/projects/{project}/tasks', 'ProjectTasksController@store');
    Route::patch('/projects/{project}/tasks/{task}', 'ProjectTasksController@update');

    // Invitations
    Route::post('/projects/{project}/invitations', 'ProjectInvitationsController@store');

    // whatever
    Route::get('/home', 'HomeController@index')->name('home');
});

Auth::routes();


