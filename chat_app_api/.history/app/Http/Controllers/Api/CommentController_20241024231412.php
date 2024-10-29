<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commentaire;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Commentaire::with('user')->get();
        return response()->json($comments);
    }

    public function show($id)
    {
        $comment = Commentaire::with('user')->find($id);
        if (!$comment) {
            return response()->json(['message' => 'Commentaire non trouvé'], 404);
        }

        return response()->json($comment);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'publication_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $comment = Commentaire::create($request->all());
        return response()->json($comment, 201);
    }

    public function update(Request $request, $id)
    {
        $comment = Commentaire::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Commentaire non trouvé'], 404);
        }

        $comment->update($request->all());
        return response()->json($comment);
    }

    public function destroy($id)
    {
        $comment = Commentaire::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Commentaire non trouvé'], 404);
        }

        $comment->delete();
        return response()->json(['message' => 'Commentaire supprimé avec succès']);
    }
}
