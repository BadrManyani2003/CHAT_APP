<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    // Ajouter un like
    public function store(Request $request, $publicationId)
    {
        $like = Like::create([
            'user_id' => $request->user()->id,
            'post_id' => $publicationId,
        ]);

        return response()->json($like, 201);
    }

    // Supprimer un like
    public function destroy($publicationId)
    {
        $like = Like::where('user_id', auth()->id())->where('post_id', $publicationId)->first();
        if (!$like) {
            return response()->json(['message' => 'Like non trouvé'], 404);
        }

        $like->delete();

        return response()->json(['message' => 'Like supprimé avec succès']);
    }
}
