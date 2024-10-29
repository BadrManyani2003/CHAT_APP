<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dislike;

class DislikeController extends Controller
{
    public function index()
    {
        $dislikes = Dislike::with('user')->get();
        return response()->json($dislikes);
    }

    public function show($id)
    {
        $dislike = Dislike::with('user')->find($id);
        if (!$dislike) {
            return response()->json(['message' => 'Dislike non trouvé'], 404);
        }

        return response()->json($dislike);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'publication_id' => 'required|integer',
        ]);

        $dislike = Dislike::create($request->all());
        return response()->json($dislike, 201);
    }

    public function destroy($id)
    {
        $dislike = Dislike::find($id);
        if (!$dislike) {
            return response()->json(['message' => 'Dislike non trouvé'], 404);
        }

        $dislike->delete();
        return response()->json(['message' => 'Dislike supprimé avec succès']);
    }
}
