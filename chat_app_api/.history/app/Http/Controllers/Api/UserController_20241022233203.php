<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Afficher la liste des utilisateurs
    public function index()
    {
        $users = User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'imgprofile' => $user->imgprofile ? asset('storage/image_profile/' . $user->imgprofile) : null,
                'birthday' => $user->birthday,
                'gender' => $user->gender,
                'address' => $user->address,
                'role' => $user->role,
            ];
        });

        return response()->json($users);
    }

    // Créer un nouvel utilisateur (réservé à l'admin)
    public function store(Request $request)
    {
        // Vérifier si l'utilisateur connecté est admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'imgprofile' => 'nullable|image',
        ]);

        $imgProfilePath = null;
        if ($request->hasFile('imgprofile')) {
            $imgProfilePath = $request->file('imgprofile')->store('image_profile', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'imgprofile' => $imgProfilePath,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'address' => $request->address,
            'role' => $request->role ?? 'user',
        ]);

        return response()->json(['message' => 'Utilisateur créé avec succès', 'user' => $user], 201);
    }

    // Afficher les détails d'un utilisateur
    public function show($id)
    {
        $user = User::findOrFail($id);
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'imgprofile' => $user->imgprofile ? asset('storage/image_profile/' . $user->imgprofile) : null,
            'birthday' => $user->birthday,
            'gender' => $user->gender,
            'address' => $user->address,
            'role' => $user->role,
        ];

        return response()->json($userData);
    }

    // Mettre à jour un utilisateur (réservé à l'admin)
    public function update(Request $request, $id)
    {
        // Vérifier si l'utilisateur connecté est admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'imgprofile' => 'nullable|image',
        ]);

        if ($request->hasFile('imgprofile')) {
            // Supprimer l'ancienne image si elle existe
            if ($user->imgprofile) {
                Storage::disk('public')->delete($user->imgprofile);
            }
            $user->imgprofile = $request->file('imgprofile')->store('image_profile', 'public');
        }

        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->birthday = $request->birthday ?? $user->birthday;
        $user->gender = $request->gender ?? $user->gender;
        $user->address = $request->address ?? $user->address;
        $user->role = $request->role ?? $user->role;

        $user->save();

        return response()->json(['message' => 'Utilisateur mis à jour avec succès', 'user' => $user]);
    }

    // Supprimer un utilisateur (réservé à l'admin)
    public function destroy($id)
    {
        // Vérifier si l'utilisateur connecté est admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $user = User::findOrFail($id);

        // Supprimer l'image de profil si elle existe
        if ($user->imgprofile) {
            Storage::disk('public')->delete($user->imgprofile);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
    }
}
