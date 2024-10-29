<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PublicationController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\DislikeController;

// Routes publiques pour l'authentification
Route::post('/register', [AuthController::class, 'register']); // Enregistrement d'un utilisateur
Route::post('/login', [AuthController::class, 'login']); // Connexion d'un utilisateur

// Route protégée pour la déconnexion (nécessite l'authentification via Sanctum)
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Routes protégées (nécessitent l'authentification avec Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Routes CRUD pour les utilisateurs
    Route::get('/users', [UserController::class, 'index']); // Liste tous les utilisateurs
    Route::get('/users/{id}', [UserController::class, 'show']); // Affiche un utilisateur spécifique
    Route::post('/users', [UserController::class, 'store']); // Crée un nouvel utilisateur
    Route::put('/users/{id}', [UserController::class, 'update']); // Met à jour un utilisateur
    Route::delete('/users/{id}', [UserController::class, 'destroy']); // Supprime un utilisateur

    // Routes CRUD pour les publications
    Route::apiResource('/publications', PublicationController::class);

    // Routes pour les commentaires d'une publication
    Route::get('/publications/comments', [CommentController::class, 'index']); // Liste les commentaires d'une publication
    Route::post('/publicationscomments', [CommentController::class, 'store']); // Crée un commentaire pour une publication
    Route::put('/comments/{id}', [CommentController::class, 'update']); // Met à jour un commentaire
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']); // Supprime un commentaire

    // Routes pour les Likes
    Route::post('/likes', [LikeController::class, 'store']); // Ajoute un like
    Route::delete('/likes', [LikeController::class, 'destroy']); // Supprime un like

    // Routes pour les Dislikes
    Route::post('/dislikes', [DislikeController::class, 'store']); // Ajoute un dislike
    Route::delete('/dislikes', [DislikeController::class, 'destroy']); // Supprime un dislike
});
