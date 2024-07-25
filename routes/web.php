<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});


use App\Http\Controllers\ToDoListController;

Route::get('/', [ToDoListController::class, 'index']);
Route::get('/tasks', [ToDoListController::class, 'getTasks']);
Route::post('/tasks', [ToDoListController::class, 'store']);
Route::put('/tasks/{task}', [ToDoListController::class, 'update']);
Route::delete('/tasks/{task}', [ToDoListController::class, 'destroy']);
