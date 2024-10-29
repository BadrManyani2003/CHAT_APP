<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    public function index()
    {
        $likes = Like::all();
        return response()->json($likes);
    }

    public function show($id)
    {
        $like = Like::find($id);
        if (!$like) {
            return response()->json(['message' => 'Like non trouvé'], 404);
        }

        return response()->json($like);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'post_id' => 'required|integer',
        ]);

        $like = Like::create($request->all());
        return response()->json($like, 201);
    }

    public function destroy($id)
    {
        $like = Like::find($id);
        if (!$like) {
            return response()->json(['message' => 'Like non trouvé'], 404);
        }

        $like->delete();
        return response()->json(['message' => 'Like supprimé avec succès']);
    }
}
