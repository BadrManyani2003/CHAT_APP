<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    // Afficher tous les utilisateurs
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Afficher un utilisateur spécifique
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        return response()->json($user);
    }

    // Créer un nouvel utilisateur (seulement pour les administrateurs)
    public function store(Request $request)
    {
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'imgprofile' => $request->imgprofile,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'address' => $request->address,
            'role' => $request->role ?? 'user',
        ]);

        return response()->json($user, 201);
    }

    // Mettre à jour un utilisateur
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        if (!auth()->user() || (auth()->user()->role !== 'admin' && auth()->id() !== $user->id)) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
        ]);

        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'imgprofile' => $request->imgprofile ?? $user->imgprofile,
            'birthday' => $request->birthday ?? $user->birthday,
            'gender' => $request->gender ?? $user->gender,
            'address' => $request->address ?? $user->address,
            'role' => $request->role ?? $user->role,
        ]);

        return response()->json($user);
    }

    // Supprimer un utilisateur
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        if (!auth()->user() || auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
    }
}
