<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\UserController;


// Routes publiques pour l'authentification
Route::post('/register', [AuthController::class, 'register']); // Enregistrement d'un utilisateur
Route::post('/login', [AuthController::class, 'login']); // Connexion d'un utilisateur

// Route protégée pour la déconnexion (nécessite l'authentification via Sanctum)
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
// Routes protégées (nécessitent l'authentification avec Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Routes CRUD pour les utilisateurs
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});
