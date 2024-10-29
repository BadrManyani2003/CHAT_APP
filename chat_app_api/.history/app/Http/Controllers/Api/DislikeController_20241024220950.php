<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dislike;
use App\Models\Publication;

class DislikeController extends Controller
{
    // Ajouter un dislike à une publication
    public function store(Request $request)
    {
        $request->validate([
            'publication_id' => 'required|exists:publications,id'
        ]);

        $publication = Publication::find($request->publication_id);

        // Vérifier si l'utilisateur a déjà disliké cette publication
        if ($publication->dislikes()->where('user_id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'Vous avez déjà disliké cette publication'], 400);
        }

        $dislike = Dislike::create([
            'publication_id' => $request->publication_id,
            'user_id' => $request->user()->id
        ]);

        return response()->json($dislike, 201);
    }

    // Supprimer un dislike
    public function destroy(Request $request)
    {
        $request->validate([
            'publication_id' => 'required|exists:publications,id'
        ]);

        $dislike = Dislike::where('publication_id', $request->publication_id)
                          ->where('user_id', $request->user()->id)
                          ->first();

        if (!$dislike) {
            return response()->json(['message' => 'Dislike non trouvé'], 404);
        }

        $dislike->delete();

        return response()->json(['message' => 'Dislike supprimé avec succès']);
    }
}
