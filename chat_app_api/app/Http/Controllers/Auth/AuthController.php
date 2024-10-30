<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Méthode d'enregistrement
    public function register(Request $request)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'imgprofile' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:50',
        ]);

        // Gestion de l'image de profil
        $imgProfilePath = $request->file('imgprofile')->store('image_profile', 'public');

        // Création de l'utilisateur
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

        // Générer l'URL complète de l'image de profil
        $imgProfileUrl = url('storage/' . $user->imgprofile);

        // Retourner l'utilisateur et l'URL de l'image
        return response()->json([
            'user' => $user,
            'imgprofile' => $imgProfileUrl, // Ajouter l'URL complète de l'image dans la réponse
        ], 201);
    }

    // Méthode de connexion
    public function login(Request $request)
    {
        // Validation des données
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Vérification des informations d'identification
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les informations d\'identification fournies sont incorrectes.'],
            ]);
        }

        // Création d'un token d'accès
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        // Générer l'URL de l'image de profil
        $imgProfileUrl = $user->imgprofile ? url('storage/' . $user->imgprofile) : asset('storage/default/default.jfif');

        // Retourner l'utilisateur et le token d'accès
        return response()->json([
            'user' => $user,
            'token' => $token,
            'imgprofile' => $imgProfileUrl, // Retourner l'URL de l'image de profil
        ], 200);
    }

    // Méthode de déconnexion
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie',
        ]);
    }
}
