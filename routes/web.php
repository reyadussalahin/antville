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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get("/", function() {
//     return view("home");
// });

// Route::get("/index", function() {
// 	return view("home");
// });


// auth routes are perfect, no need to change them
Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home'); // we're not using it


Route::get("/", "TasksController@index");
Route::get("/home", "TasksController@index")->name("home");
Route::get("/index", "TasksController@index")->name("index");


// its better to declare pull before "tasks/{id}" and also check "tasks/{id}"'s {id} with regular expression, otherwise, there will be a problem matching "tasks/pull" with "tasks/{id}"
Route::get("/tasks/pull", "TasksController@pull")->name("tasks.pull");


Route::get("/tasks/create", "TasksController@create")->name("tasks.create");
Route::post("/tasks/store", "TasksController@store")->name("tasks.store"); // note, it is post
Route::get("/tasks/{id}", "TasksController@show")->where("id", "[0-9]+")->name("tasks.show"); // note: the regular expression chek for "id". it's important. otherwise, it creates problem with "tasks/pull", if we declare "tasks/pull" after "/tasks/{id}"
Route::post("/tasks/{id}/extend", "TasksController@extend")->where("id", "[0-9]+")->name("tasks.extend");
Route::post("/tasks/{id}/edit", "TasksController@edit")->where("id", "[0-9]+")->name("tasks.edit"); 

Route::get("/tasks/{id}/finished", "TasksController@finished")->where("id", "[0-9]+")->name("tasks.finished");


Route::post("/tasks/{taskId}/milestones/store", "MilestonesController@store")->where("id", "[0-9]+")->name("milestones.store");
Route::get("/tasks/{taskId}/milestones/pull", "MilestonesController@pull")->where("id", "[0-9]+")->name("milestones.pull");

