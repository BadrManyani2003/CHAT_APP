<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');




Route::middleware('auth:sanctum')->group(function () {
    // Afficher tous les utilisateurs
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // Afficher un utilisateur spécifique
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

    // Créer un nouvel utilisateur (seulement pour les administrateurs)
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    // Mettre à jour un utilisateur
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{id}', [UserController::class, 'update'])->name('users.update');

    // Supprimer un utilisateur
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});
