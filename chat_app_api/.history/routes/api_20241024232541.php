use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PublicationController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\DislikeController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Auth\AuthController;

// Routes d'authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Routes sécurisées avec Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Routes pour les utilisateurs
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Routes pour les publications
    Route::get('/publications', [PublicationController::class, 'index']);
    Route::get('/publications/{id}', [PublicationController::class, 'show']);
    Route::post('/publications', [PublicationController::class, 'store']);
    Route::put('/publications/{id}', [PublicationController::class, 'update']);
    Route::delete('/publications/{id}', [PublicationController::class, 'destroy']);

    // Routes pour les likes
    Route::get('/likes', [LikeController::class, 'index']);
    Route::get('/likes/{id}', [LikeController::class, 'show']);
    Route::post('/likes', [LikeController::class, 'store']);
    Route::delete('/likes/{id}', [LikeController::class, 'destroy']);

    // Routes pour les dislikes
    Route::get('/dislikes', [DislikeController::class, 'index']);
    Route::get('/dislikes/{id}', [DislikeController::class, 'show']);
    Route::post('/dislikes', [DislikeController::class, 'store']);
    Route::delete('/dislikes/{id}', [DislikeController::class, 'destroy']);

    // Routes pour les commentaires
    Route::get('/comments', [CommentController::class, 'index']);
    Route::get('/comments/{id}', [CommentController::class, 'show']);
    Route::post('/comments', [CommentController::class, 'store']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    // Routes pour les messages
    Route::get('/messages', [MessageController::class, 'index']);
    Route::get('/messages/{id}', [MessageController::class, 'show']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::put('/messages/{id}', [MessageController::class, 'update']);
    Route::delete('/messages/{id}', [MessageController::class, 'destroy']);
});
