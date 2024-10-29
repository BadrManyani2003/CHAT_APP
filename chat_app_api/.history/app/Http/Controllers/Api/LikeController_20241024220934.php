<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Publication;

class LikeController extends Controller
{
    // Ajouter un like à une publication
    public function store(Request $request)
    {
        $request->validate([
            'publication_id' => 'required|exists:publications,id'
        ]);

        $publication = Publication::find($request->publication_id);

        // Vérifier si l'utilisateur a déjà aimé cette publication
        if ($publication->likes()->where('user_id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'Vous avez déjà aimé cette publication'], 400);
        }

        $like = Like::create([
            'publication_id' => $request->publication_id,
            'user_id' => $request->user()->id
        ]);

        return response()->json($like, 201);
    }

    // Supprimer un like
    public function destroy(Request $request)
    {
        $request->validate([
            'publication_id' => 'required|exists:publications,id'
        ]);

        $like = Like::where('publication_id', $request->publication_id)
                    ->where('user_id', $request->user()->id)
                    ->first();

        if (!$like) {
            return response()->json(['message' => 'Like non trouvé'], 404);
        }

        $like->delete();

        return response()->json(['message' => 'Like supprimé avec succès']);
    }
}
