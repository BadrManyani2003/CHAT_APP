<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PublicationController;
use App\Http\Controllers\Api\CommentaireController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\DislikeController;
use App\Http\Controllers\Api\MessageController;


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




// Routes pour les publications
Route::apiResource('publications', PublicationController::class);

// Routes pour les commentaires d'une publication
Route::get('publications/{publicationId}/commentaires', [CommentaireController::class, 'index']);
Route::post('publications/{publicationId}/commentaires', [CommentaireController::class, 'store']);
Route::delete('commentaires/{id}', [CommentaireController::class, 'destroy']);

// Routes pour les likes et dislikes
Route::post('publications/{publicationId}/likes', [LikeController::class, 'store']);
Route::delete('publications/{publicationId}/likes', [LikeController::class, 'destroy']);
Route::post('publications/{publicationId}/dislikes', [DislikeController::class, 'store']);
Route::delete('publications/{publicationId}/dislikes', [DislikeController::class, 'destroy']);

// Routes pour les messages
Route::get('messages/{userId}', [MessageController::class, 'index']);
Route::post('messages', [MessageController::class, 'store']);
Route::put('messages/{id}', [MessageController::class, 'update']);
});