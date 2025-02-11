<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

// Route d'inscription (register)
Route::post('/register', [AuthController::class, 'register']);

// Route de connexion (login)
Route::post('/login', [AuthController::class, 'login']);

// Groupe de routes protégées par Sanctum
Route::middleware('auth:sanctum')->group(function () {
    
    // Route de déconnexion (logout)
    Route::post('/logout', [AuthController::class, 'logout']);

});