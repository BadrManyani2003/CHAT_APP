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
        $request->validate([
            'content' => 'required|string',
            'publication_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $comment = Comment::create($request->all());
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
            return response()->json(['message' => 'Comment non trouvé'], 404);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment supprimé avec succès']);
    }
}
