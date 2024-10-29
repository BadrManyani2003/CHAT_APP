<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Publication;

class CommentController extends Controller
{
    // Ajouter un commentaire à une publication
    public function store(Request $request)
    {
        $request->validate([
            'publication_id' => 'required|exists:publications,id',
            'content' => 'required|string'
        ]);

        $comment = Comment::create([
            'publication_id' => $request->publication_id,
            'user_id' => $request->user()->id,
            'content' => $request->content
        ]);

        return response()->json($comment, 201);
    }

    // Mettre à jour un commentaire
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment || $comment->user_id != $request->user()->id) {
            return response()->json(['message' => 'Commentaire non trouvé ou vous n\'êtes pas autorisé à le modifier'], 403);
        }

        $request->validate([
            'content' => 'required|string'
        ]);

        $comment->content = $request->content;
        $comment->save();

        return response()->json($comment);
    }

    // Supprimer un commentaire
    public function destroy($id, Request $request)
    {
        $comment = Comment::find($id);

        if (!$comment || $comment->user_id != $request->user()->id) {
            return response()->json(['message' => 'Commentaire non trouvé ou vous n\'êtes pas autorisé à le supprimer'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Commentaire supprimé avec succès']);
    }
}
