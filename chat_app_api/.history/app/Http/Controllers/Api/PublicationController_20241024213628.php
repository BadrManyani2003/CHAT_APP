<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\Commentaire; // Importer le modèle Commentaire
use App\Models\Like; // Importer le modèle Like
use App\Models\Dislike; // Importer le modèle Dislike
use Illuminate\Support\Facades\Storage;

class PublicationController extends Controller
{
    // Afficher toutes les publications avec le nombre de likes, dislikes et les commentaires
    public function index()
    {
        $publications = Publication::withCount(['likes', 'dislikes']) // Compter les likes et dislikes
            ->with(['commentaires' => function ($query) {
                $query->with('user'); // Charger les utilisateurs associés aux commentaires
            }])
            ->get()
            ->map(function ($publication) {
                return [
                    'id' => $publication->id,
                    'content' => $publication->content,
                    'img' => $publication->img ? asset('storage/image_publication/' . $publication->img) : null,
                    'user_id' => $publication->user_id,
                    'likes_count' => $publication->likes_count, // Nombre de likes
                    'dislikes_count' => $publication->dislikes_count, // Nombre de dislikes
                    'comments' => $publication->commentaires->map(function ($comment) {
                        return [
                            'id' => $comment->id,
                            'content' => $comment->content,
                            'user_id' => $comment->user->id, // ID de l'utilisateur du commentaire
                            'user_name' => $comment->user->name, // Nom de l'utilisateur du commentaire
                            'created_at' => $comment->created_at,
                            'updated_at' => $comment->updated_at,
                        ];
                    }),
                    'created_at' => $publication->created_at,
                    'updated_at' => $publication->updated_at,
                ];
            });

        return response()->json($publications);
    }

    // Afficher une publication spécifique avec les détails
    public function show($id)
    {
        $publication = Publication::withCount(['likes', 'dislikes'])
            ->with(['commentaires' => function ($query) {
                $query->with('user');
            }])
            ->find($id);

        if (!$publication) {
            return response()->json(['message' => 'Publication non trouvée'], 404);
        }

        $publicationData = [
            'id' => $publication->id,
            'content' => $publication->content,
            'img' => $publication->img ? asset('storage/image_publication/' . $publication->img) : null,
            'user_id' => $publication->user_id,
            'likes_count' => $publication->likes_count,
            'dislikes_count' => $publication->dislikes_count,
            'comments' => $publication->commentaires->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user_id' => $comment->user->id,
                    'user_name' => $comment->user->name,
                    'created_at' => $comment->created_at,
                    'updated_at' => $comment->updated_at,
                ];
            }),
            'created_at' => $publication->created_at,
            'updated_at' => $publication->updated_at,
        ];

        return response()->json($publicationData);
    }

    // Créer une nouvelle publication
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imgPath = null;
        if ($request->hasFile('img')) {
            $imgPath = $request->file('img')->store('image_publication', 'public');
        }

        $publication = Publication::create([
            'content' => $request->content,
            'img' => $imgPath,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($publication, 201);
    }

    // Mettre à jour une publication existante
    public function update(Request $request, $id)
    {
        $publication = Publication::find($id);
        if (!$publication) {
            return response()->json(['message' => 'Publication non trouvée'], 404);
        }

        $request->validate([
            'content' => 'sometimes|required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('img')) {
            if ($publication->img) {
                Storage::disk('public')->delete($publication->img);
            }
            $publication->img = $request->file('img')->store('image_publication', 'public');
        }

        $publication->content = $request->content ?? $publication->content;
        $publication->save();

        return response()->json($publication);
    }

    // Supprimer une publication
    public function destroy($id)
    {
        $publication = Publication::find($id);
        if (!$publication) {
            return response()->json(['message' => 'Publication non trouvée'], 404);
        }

        if ($publication->img) {
            Storage::disk('public')->delete($publication->img);
        }

        $publication->delete();

        return response()->json(['message' => 'Publication supprimée avec succès']);
    }
}
