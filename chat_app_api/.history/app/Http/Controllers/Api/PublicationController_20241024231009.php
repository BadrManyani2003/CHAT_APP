<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Publication;

class PublicationController extends Controller
{
    public function index()
    {
        $publications = Publication::all();
        return response()->json($publications);
    }

    public function show($id)
    {
        $publication = Publication::find($id);
        if (!$publication) {
            return response()->json(['message' => 'Publication non trouvée'], 404);
        }

        return response()->json($publication);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $publication = Publication::create($request->all());
        return response()->json($publication, 201);
    }

    public function update(Request $request, $id)
    {
        $publication = Publication::find($id);
        if (!$publication) {
            return response()->json(['message' => 'Publication non trouvée'], 404);
        }

        $publication->update($request->all());
        return response()->json($publication);
    }

    public function destroy($id)
    {
        $publication = Publication::find($id);
        if (!$publication) {
            return response()->json(['message' => 'Publication non trouvée'], 404);
        }

        $publication->delete();
        return response()->json(['message' => 'Publication supprimée avec succès']);
    }
}
