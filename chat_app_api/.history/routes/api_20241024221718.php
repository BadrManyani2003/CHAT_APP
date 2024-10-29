<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PublicationController;
use App\Http\Controllers\Api\CommentController;
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
    Route::get('/users', [UserController::class, 'index']); // Liste des utilisateurs
    Route::get('/users/{id}', [UserController::class, 'show']); // Afficher un utilisateur spécifique
    Route::post('/users', [UserController::class, 'store']); // Créer un nouvel utilisateur
    Route::put('/users/{id}', [UserController::class, 'update']); // Mettre à jour un utilisateur
    Route::delete('/users/{id}', [UserController::class, 'destroy']); // Supprimer un utilisateur

    // Routes pour les publications (CRUD complet avec apiResource)
    Route::apiResource('publications', PublicationController::class);

    // Routes pour les commentaires d'une publication
    Route::get('publications/{publicationId}/comments', [CommentController::class, 'index']); // Liste des commentaires d'une publication
    Route::post('publications/{publicationId}/comments', [CommentController::class, 'store']); // Ajouter un commentaire
    Route::put('comments/{id}', [CommentController::class, 'update']); // Modifier un commentaire
    Route::delete('comments/{id}', [CommentController::class, 'destroy']); // Supprimer un commentaire

    // Routes pour les likes d'une publication
    Route::post('publications/{publicationId}/likes', [LikeController::class, 'store']); // Ajouter un like
    Route::delete('publications/{publicationId}/likes', [LikeController::class, 'destroy']); // Supprimer un like

    // Routes pour les dislikes d'une publication
    Route::post('publications/{publicationId}/dislikes', [DislikeController::class, 'store']); // Ajouter un dislike
    Route::delete('publications/{publicationId}/dislikes', [DislikeController::class, 'destroy']); // Supprimer un dislike

    // Routes pour les messages
    Route::get('messages/{userId}', [MessageController::class, 'index']); // Liste des messages pour un utilisateur spécifique
    Route::post('messages', [MessageController::class, 'store']); // Envoyer un message
    Route::put('messages/{id}', [MessageController::class, 'update']); // Mettre à jour un message
    Route::delete('messages/{id}', [MessageController::class, 'destroy']); // Supprimer un message
});
