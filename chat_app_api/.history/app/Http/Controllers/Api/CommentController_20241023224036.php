<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commentaire;

class CommentaireController extends Controller
{
    // Afficher tous les commentaires d'une publication
    public function index($publicationId)
    {
        $commentaires = Commentaire::where('postid', $publicationId)->get()->map(function ($commentaire) {
            return [
                'id' => $commentaire->id,
                'content' => $commentaire->content,
                'user_id' => $commentaire->userid,
                'publication_id' => $commentaire->postid,
                'created_at' => $commentaire->created_at,
                'updated_at' => $commentaire->updated_at,
            ];
        });

        return response()->json($commentaires);
    }

    // Créer un commentaire
    public function store(Request $request, $publicationId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $commentaire = Commentaire::create([
            'content' => $request->content,
            'postid' => $publicationId,
            'userid' => $request->user()->id,
        ]);

        return response()->json($commentaire, 201);
    }

    // Supprimer un commentaire
    public function destroy($id)
    {
        $commentaire = Commentaire::find($id);
        if (!$commentaire) {
            return response()->json(['message' => 'Commentaire non trouvé'], 404);
        }

        $commentaire->delete();

        return response()->json(['message' => 'Commentaire supprimé avec succès']);
    }
}
