<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;

// Routes d'authetifications
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // Routes user
    Route::get('/users', [UserController::class, 'allUsers']);
    Route::get('/user/{id}', [UserController::class, 'userByID']);
    Route::post('/user', [UserController::class, 'createUser']);
    Route::put('/user/{id}', [UserController::class, 'updateUser']);
    Route::delete('/user/{id}', [UserController::class, 'deleteUser']);

    //Routes magasins
    Route::get('/stores', [StoreController::class, 'AllStores']);
    Route::get('/store/{id}', [StoreController::class, 'StoreByID']);
    Route::post('/store', [StoreController::class, 'createStore']);
    Route::put('/store/{id}', [StoreController::class, 'updateStore']);
    Route::delete('/store/{id}', [StoreController::class, 'deleteStore']);
});
