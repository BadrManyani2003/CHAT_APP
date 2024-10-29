<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dislike;

class DislikeController extends Controller
{
    // Ajouter un dislike
    public function store(Request $request, $publicationId)
    {
        $dislike = Dislike::create([
            'user_id' => $request->user()->id,
            'post_id' => $publicationId,
        ]);

        return response()->json($dislike, 201);
    }

    // Supprimer un dislike
    public function destroy($publicationId)
    {
        $dislike = Dislike::where('user_id', auth()->id())->where('post_id', $publicationId)->first();
        if (!$dislike) {
            return response()->json(['message' => 'Dislike non trouvé'], 404);
        }

        $dislike->delete();

        return response()->json(['message' => 'Dislike supprimé avec succès']);
    }
}
