<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('user')->get();
        return response()->json($comments);
    }

    public function show($id)
    {
        $comment = Comment::with('user')->find($id);
        if (!$comment) {
            return response()->json(['message' => 'Commentaire non trouvé'], 404);
        }

        return response()->json($comment);
    }

    public function store(Request $request)
    {
        // Valider les champs de la requête
        $request->validate([
            'content' => 'required|string',
            'publication_id' => 'required|integer|exists:publications,id', // Validation de l'existence de publication_id
        ]);

        // Récupérer l'utilisateur authentifié
        $user = auth()->user();

        // Créer le commentaire avec l'ID de l'utilisateur authentifié et publication_id
        $comment = Comment::create([
            'content' => $request->content,
            'publication_id' => $request->publication_id, // Assurez-vous que cette ligne est présente
            'user_id' => $user->id,  // ID de l'utilisateur authentifié
        ]);

        return response()->json($comment, 201);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Commentaire non trouvé'], 404);
        }

        $comment->update($request->all());
        return response()->json($comment);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Commentaire non trouvé'], 404);
        }

        $comment->delete();
        return response()->json(['message' => 'Commentaire supprimé avec succès']);
    }
}
