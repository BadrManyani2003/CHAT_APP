<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Publication;
use Illuminate\Support\Facades\Storage;

class PublicationController extends Controller
{
    // Afficher toutes les publications
    public function index()
    {
        $publications = Publication::all()->map(function ($publication) {
            return [
                'id' => $publication->id,
                'content' => $publication->content,
                'img' => $publication->img ? asset('storage/image_publication/' . $publication->img) : null,
                'user_id' => $publication->user_id,
                'created_at' => $publication->created_at,
                'updated_at' => $publication->updated_at,
            ];
        });

        return response()->json($publications);
    }

    // Afficher une publication spécifique
    public function show($id)
    {
        $publication = Publication::find($id);
        if (!$publication) {
            return response()->json(['message' => 'Publication non trouvée'], 404);
        }

        $publicationData = [
            'id' => $publication->id,
            'content' => $publication->content,
            'img' => $publication->img ? asset('storage/image_publication/' . $publication->img) : null,
            'user_id' => $publication->user_id,
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
