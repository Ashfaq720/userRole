<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {return view('welcome');});

Route::post('/createUser', [UserController::class, 'createUser']);

Route::post('/createRole', [UserController::class, 'createRole']);

Route::post('/createPermission', [UserController::class, 'createPermission']);

Route::post('/assignPermissionToRole', [UserController::class, 'assignPermissionToRole']);

Route::post('/assignRoleToUser', [UserController::class, 'assignRoleToUser']);

Route::post('/login', [UserController::class, 'login']);


//blog
Route::post('/readBlog', [PostController::class, 'readBlog'])->middleware('auth','permission:read-blog');
Route::post('/createBlog', [PostController::class, 'createBlog'])->middleware('auth','permission:create-blog');
Route::post('/deleteBlog', [PostController::class, 'deleteBlog'])->middleware('auth','permission:delete-blog');
Route::post('/updateBlog', [PostController::class, 'updateBlog'])->middleware('auth','permission:update-blog');



